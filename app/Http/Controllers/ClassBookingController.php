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

class ClassBookingController extends Controller
{
    // Formulario público
    public function create()
    {
        return view('bookings.create');
    }

    // Guardar reserva
    public function store(StoreClassBookingRequest $request)
    {
        // 1) Datos validados
        $data = $request->validated();

        // 2) Evitar franja ocupada: no permitir si ya existe otra reserva para la misma
        //    fecha/hora cuyo estado NO sea 'cancelled' o 'rejected' (p.ej. pending/confirmed)
        $exists = ClassBooking::where('class_date', $data['class_date'])
            ->where('class_time', $data['class_time'])
            ->whereNotIn('status', ['cancelled', 'rejected'])
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
            'name'       => $data['name'],
            'email'      => $data['email'],
            'phone'      => $data['phone'] ?? null,
            'notes'      => $data['notes'] ?? null,
            'status'     => 'pending',
        ];

        // Asignar user_id si hay usuario autenticado
        if (Auth::check()) {
            $payload['user_id'] = Auth::id();
        }

        // Mapear consentimiento GDPR si viene en el request
        if (isset($data['gdpr']) && $data['gdpr']) {
            $payload['gdpr_given'] = true;
            $payload['gdpr_at'] = now();
        }

        $booking = ClassBooking::create($payload);

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
                            'name' => 'Reserva clase - ' . $booking->name,
                        ],
                        'unit_amount' => 2500, // 25.00 EUR in cents
                    ],
                    'quantity' => 1,
                ]],
                'customer_email' => $booking->email,
                'metadata' => [
                    'booking_id' => $booking->id,
                ],
                'success_url' => route('bookings.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('bookings.create'),
            ]);
            $booking->stripe_session_id = $session->id;
            $booking->save();
            // Log session details for traceability (no datos sensibles)
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

            // redirect to Stripe Checkout page
            return redirect($session->url);
        } catch (\Throwable $e) {
            Log::error('Stripe Checkout creation failed: ' . $e->getMessage());
            return back()->with('error', 'No se pudo iniciar el pago. Inténtalo de nuevo más tarde.');
        }
    }

    public function success()
    {
        return view('bookings.success');
    }

    // Devuelve las horas disponibles para una fecha dada en formato JSON
    public function availability(Request $request)
    {
        $date = $request->query('date');
        $exceptId = $request->query('except'); // optional booking id to ignore (for edit)

        if (! $date) {
            return response()->json(['error' => 'date parameter required'], 422);
        }

        // Horas posibles (en punto) de 09:00 a 21:00
        $all = [];
        foreach (range(9, 21) as $h) {
            $hh = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00';
            $all[] = $hh;
        }

        // Obtener reservas no canceladas/rechazadas para esa fecha
        $query = ClassBooking::where('class_date', $date)
            ->whereNotIn('status', ['cancelled', 'rejected']);

        if ($exceptId) {
            $query->where('id', '!=', $exceptId);
        }

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

        $available = array_values(array_filter($all, function ($t) use ($taken, $blockedTimes, $date) {
            // Excluir si ya tomado o bloqueado
            if (in_array($t, $taken) || in_array($t, $blockedTimes)) return false;

            // Si la fecha es hoy, excluir horas que ya hayan pasado respecto a la hora actual
            try {
                $d = Carbon::parse($date);
                if ($d->isToday()) {
                    [$H, $M] = explode(':', substr($t, 0, 5));
                    $tMin = intval($H) * 60 + intval($M);
                    $now = Carbon::now();
                    $nowMin = $now->hour * 60 + $now->minute;
                    // Si la franja es anterior o igual al momento actual, no mostrarla
                    if ($tMin <= $nowMin) return false;
                }
            } catch (\Throwable $e) {
                // si falla el parseo, no hacemos el filtrado por hora
            }

            return true;
        }));

        return response()->json(['available' => $available]);
    }

    /**
     * Permite unirse a la videollamada de una reserva.
     * Puede accederse si la URL firmada es válida o si el usuario autenticado
     * es el propietario de la reserva o un admin.
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
            if ($user->is_admin || $user->email === $booking->email || $user->id === $booking->user_id) {
                if (! empty($booking->meeting_url)) {
                    return redirect()->away($booking->meeting_url);
                }
                abort(404, 'No hay enlace de videollamada asociado a esta reserva.');
            }
        }

        abort(403);
    }
}
