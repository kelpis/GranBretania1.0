<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClassBookingRequest;
use App\Models\ClassBooking;
use App\Models\AvailabilitySlot;
use App\Notifications\BookingReceived;
use App\Notifications\BookingAdminNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Stripe\StripeClient;
use Illuminate\Http\Request;
use Carbon\Carbon;
//CONTROLADOR RESERVA CLASE USER
class ClassBookingController extends Controller
{
    // Formulario público
    public function create()
    {
        return view('user.bookings.create');
    }




    // Guardar reserva
    public function store(StoreClassBookingRequest $request)
    {
        //Datos validados


        $data = $request->validated();
        $currentUserId = Auth::id();


        //Evitar franja ocupada: no permitir si ya existe otra reserva para la misma
        //fecha/hora cuyo estado NO sea 'cancelled' o 'rejected' 
        $exists = ClassBooking::where('class_date', $data['class_date'])
            ->where('class_time', $data['class_time'])
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->where(function ($q) use ($currentUserId) {
                // Criterio 1: cualquier reserva ya pagada ocupa la franja
                $q->where('paid', true)
                  // Criterio 2: o existe un hold activo que no pertenece al usuario actual
                  ->orWhere(function ($q2) use ($currentUserId) {
                      $q2->whereNotNull('reserved_until')
                         ->where('reserved_until', '>', now())
                         ->where(function ($q3) use ($currentUserId) {
                             $q3->whereNull('user_id')
                                ->orWhere('user_id', '!=', $currentUserId);
                         });
                  });
            })
            ->exists();

   

        if ($exists) {
            return back()
                ->withErrors(['class_time' => 'Lo sentimos — esa franja ya está ocupada.'])
                ->withInput();
        }

        // 3) Crear en pending
        // Antes de crear, comprobar que la franja no esté bloqueada por admin
        $isBlocked = AvailabilitySlot::where('date', $data['class_date'])
            ->where('status', 'blocked')
            ->get()
            ->filter(function ($slot) use ($data) {
                // convertir tiempos a minutos para comparar rangos
                [$h, $m] = explode(':', substr($data['class_time'], 0, 5));
                $tMin = intval($h) * 60 + intval($m);

                [$sH, $sM] = explode(':', substr($slot->start_time, 0, 5));
                [$eH, $eM] = explode(':', substr($slot->end_time, 0, 5));
                $sMin = intval($sH) * 60 + intval($sM);
                // soportar end_time == '24:00'
                $eMin = intval($eH) * 60 + intval($eM);

                return $tMin >= $sMin && $tMin < $eMin;
            })->isNotEmpty();

        if ($isBlocked) {
            return back()->withErrors(['class_time' => 'Esa franja está bloqueada por el administrador.'])->withInput();
        }
        $payload = [
            'class_date' => $data['class_date'],
            'class_time' => $data['class_time'],
            'notes'      => $data['notes'] ?? null,
            'status'     => 'pending',
        ];

        // Usuario autenticado es obligatorio: asignar su id
        $payload['user_id'] = Auth::id();

        // Mapear consentimiento GDPR si viene en el request
        if (isset($data['gdpr']) && $data['gdpr']) {
            $payload['gdpr_given'] = true;
            $payload['gdpr_at'] = now();
        }

        $booking = ClassBooking::create($payload);

        // Marcar un hold temporal para evitar overbooking (configurado .env)
        try {
            if (env('ENABLE_RESERVATION_HOLDS', true)) {
                $minutes = (int) env('RESERVATION_HOLD_MINUTES', 30);
                $booking->reserved_until = now()->addMinutes($minutes);
                $booking->save();
            }
        } catch (\Throwable $e) {
            // No bloquear el flujo por un fallo al guardar el hold
            Log::warning('Failed to set reservation hold: ' . $e->getMessage());
        }

        // Crear sesión de Stripe Checkout y redirigir al usuario para pagar
        try {
            $stripeSecret = config('services.stripe.secret');
            // advertencia temprana si la secret parece ausente o no tiene el prefijo esperado
            if (empty($stripeSecret) || !preg_match('/^sk_/', $stripeSecret)) {
                Log::warning('Stripe secret looks missing or unusual', ['secret_present' => empty($stripeSecret) ? false : true]);
            }
            $stripe = new StripeClient($stripeSecret);
            $session = $stripe->checkout->sessions->create([
                'payment_method_types' => ['card'],
                'mode' => 'payment',
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Reserva clase - ' . (Auth::user()->name ?? ($booking->name ?? 'Reserva')),
                        ],
                        'unit_amount' => 2500, // 25.00 EUR en centimos , requisito stripe
                    ],
                    'quantity' => 1,
                ]],
                // Forzar email del cliente desde el usuario autenticado
                'customer_email' => Auth::user()->email ?? ($booking->email ?? null),
                'metadata' => [
                    'booking_id' => $booking->id,
                ],
                'success_url' => route('bookings.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('bookings.create'),
            ]);
            $booking->stripe_session_id = $session->id;
            $booking->save();
            // Log de control
            try {
                Log::info('Stripe Checkout created', [
                    'session_id' => $session->id ?? null,
                    'session_url' => $session->url ?? null,
                    'booking_id' => $booking->id,
                    'metadata' => is_object($session->metadata) ? (array)$session->metadata : $session->metadata,
                ]);
            } catch (\Throwable $e) {
                // no bloquear el flujo por errores de logging
            }

            // redirigir a Stripe Checkout 
            return redirect($session->url);
        } catch (\Throwable $e) {
            Log::error('Stripe Checkout creation failed: ' . $e->getMessage());
            return back()->with('error', 'No se pudo iniciar el pago. Inténtalo de nuevo más tarde.');
        }
    }


    //Vista exito reserva
    public function success()
    {
        return view('user.bookings.success');
    }




    // Devuelve las horas disponibles para una fecha dada en formato JSON
    public function availability(Request $request)
    {
        //Entrada y validacion
        $date = $request->query('date');
        $exceptId = $request->query('except'); //Util para editar

        if (! $date) {
            return response()->json(['error' => 'date parameter required'], 422);
        }

        // Horas posibles (en punto) de 09:00 a 21:00
        $all = [];
        foreach (range(9, 21) as $h) {
            $hh = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00';
            $all[] = $hh;
        }

        // Obtener reservas no canceladas para esa fecha: consideradas tomadas si están pagadas
        // o si tienen un hold activo (`reserved_until` en el futuro)
        // Excluir reservas que pertenezcan al usuario actual para permitir reintentos de pago
        $currentUserId = Auth::id();
        $query = ClassBooking::where('class_date', $date)
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->where(function ($q) use ($currentUserId) {
                // cualquier reserva pagada ocupa la franja
                $q->where('paid', true)
                  // o un hold activo que no pertenece al usuario actual
                  ->orWhere(function ($q2) use ($currentUserId) {
                      $q2->whereNotNull('reserved_until')
                         ->where('reserved_until', '>', now())
                         ->where(function ($q3) use ($currentUserId) {
                             $q3->whereNull('user_id')
                                ->orWhere('user_id', '!=', $currentUserId);
                         });
                  });
            });

        if ($exceptId) {
            $query->where('id', '!=', $exceptId);
        }
        //Transforma cada class_time a formato HH::MM
        $taken = $query->get()->map(function ($b) {
            return substr($b->class_time, 0, 5);
        })->toArray();

        // Excluir franjas bloqueadas por admin
        $blockedSlots = AvailabilitySlot::where('date', $date)
            ->where('status', 'blocked')
            ->get();

        // Convertir a minutos para comparar
        $toMinutes = function ($time) {
            [$H, $M] = explode(':', substr($time, 0, 5));
            return intval($H) * 60 + intval($M);
        };
        //Convierte tiempos a minutos y marca cualquier t que caiga dentro de un rango bloqueado.
        $blockedTimes = [];
        foreach ($all as $t) {
            $tMin = $toMinutes($t);
            foreach ($blockedSlots as $slot) {
                $sMin = $toMinutes($slot->start_time);
                $eMin = $toMinutes($slot->end_time);
                if ($tMin >= $sMin && $tMin < $eMin) {
                    $blockedTimes[] = $t;
                    break;
                }
            }
        }
        //Filtrado final y exclusion de franjas no disponibles
        $available = array_values(array_filter($all, function ($t) use ($taken, $blockedTimes, $date) {
            // Excluir si ya tomado o bloqueado
            if (in_array($t, $taken) || in_array($t, $blockedTimes)) return false;

            // Excluir franjas con menos de 5 horas de antelación (para hoy o cualquier fecha cercana)
            try {
                $time = substr($t, 0, 5);
                $classDT = Carbon::parse($date . ' ' . $time);
                $now = Carbon::now();
                $minutesUntil = $now->diffInMinutes($classDT, false);

                // Si faltan menos de 300 minutos (5 horas) o la franja ya está en el pasado, excluirla
                if ($minutesUntil < 300) return false;
            } catch (\Throwable $e) {
                // si falla el parseo, no hacemos el filtrado por tiempo
            }

            return true;
        }));

        return response()->json(['available' => $available]);
    }





    /*Permite unirse a la videollamada de una reserva.
    Puede accederse si la URL firmada es válida o si el usuario autenticado es el propietario de la reserva o un admin.
     */
    public function join(Request $request, ClassBooking $booking)
    {
        // Permitir si la petición tiene firma válida
        if ($request->hasValidSignature()) {
            $url = $booking->meeting_url ?? null;
            return redirect()->away($url ?: url('/'));
        }

        // Si no, permitir a usuarios autenticados que sean admin o dueños
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->is_admin || $user->id === $booking->user_id) {
                if (! empty($booking->meeting_url)) {
                    return redirect()->away($booking->meeting_url);
                }
                abort(404, 'No hay enlace de videollamada asociado a esta reserva.');
            }
        }

        abort(403);
    }
}
