<?php

namespace App\Http\Controllers;

use App\Models\ClassBooking;
use App\Models\TranslationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;
use App\Notifications\BookingCancelledNonRefundableNotification;
use App\Notifications\BookingCancelledByUserRefundableNotification;
use App\Notifications\BookingUpdatedNotification;
use App\Notifications\BookingAdminUpdatedNotification;
use App\Notifications\BookingCancelledNotification;
use App\Notifications\BookingAdminCancelledNotification;
use App\Models\AvailabilitySlot;

// Controlador para el panel de usuario de reservas.
// Gestiona la visualización, edición, actualización y cancelación de reservas de clases,
// incluyendo lógica de reembolsos vía Stripe y notificaciones.

class UserBookingController extends Controller
{
    // Constructor: aplica middleware de autenticación a todas las rutas de este controlador,
    // para que solo usuarios loggeados puedan acceder
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Lista las reservas del usuario: clases futuras, historial y traducciones.
    public function index()
    {
        $user = Auth::user();

        // Obtener clases futuras (pagadas y con fecha >= hoy).
        $upcoming = ClassBooking::where('user_id', $user->id)
            ->where('paid', true)
            ->whereDate('class_date', '>=', now()->toDateString())
            ->orderBy('class_date')
            ->orderBy('class_time')
            ->get();

        // Obtener clases pasadas (historial, pagadas y con fecha < hoy, limitado a 10).
        $history = ClassBooking::where('user_id', $user->id)
            ->where('paid', true)
            ->whereDate('class_date', '<', now()->toDateString())
            ->orderBy('class_date', 'desc')
            ->orderBy('class_time', 'desc')
            ->limit(10)
            ->get();

        // Obtener traducciones del usuario.
        $translations = TranslationRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Pasar datos a la vista.
        return view('user.bookings.index', compact('upcoming', 'history', 'translations'));
    }

    // Formulario para editar una reserva (verifica que pertenezca al usuario).
    public function edit(ClassBooking $booking)
    {
        $this->authorizeBooking($booking);

        return view('user.bookings.edit', compact('booking'));
    }

    // Actualiza la reserva con validaciones y restricciones (conflictos, bloqueos de franjas).
    public function update(Request $request, ClassBooking $booking)
    {
        $this->authorizeBooking($booking);

        // Validar datos de entrada.
        $data = $request->validate([
            'class_date' => 'required|date',
            'class_time' => 'required',
            'name' => 'required|string|max:255',
            'phone' => ['nullable', 'string', 'max:50', 'regex:/^[0-9+\\s\\-()]+$/'],
            'notes' => 'nullable|string|max:1000',
        ]);

        // Verificar que no haya conflictos con otras reservas pagadas.
        $conflict = ClassBooking::where('class_date', $data['class_date'])
            ->where('class_time', $data['class_time'])
            ->where('id', '!=', $booking->id)
            ->where('paid', true)
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->exists();

        if ($conflict) {
            return back()->withErrors(['class_time' => 'Esa franja ya está ocupada.'])->withInput();
        }

        // Verificar que la franja no esté bloqueada por el administrador.
        $isBlocked = AvailabilitySlot::where('date', $data['class_date'])
            ->where('status', 'blocked')
            ->get()
            ->filter(function ($slot) use ($data) {
                [$h, $m] = explode(':', substr($data['class_time'], 0, 5));
                $tMin = intval($h) * 60 + intval($m);

                [$sH, $sM] = explode(':', substr($slot->start_time, 0, 5));
                [$eH, $eM] = explode(':', substr($slot->end_time, 0, 5));
                $sMin = intval($sH) * 60 + intval($sM);
                $eMin = intval($eH) * 60 + intval($eM);

                return $tMin >= $sMin && $tMin < $eMin;
            })->isNotEmpty();

        if ($isBlocked) {
            return back()->withErrors(['class_time' => 'Esa franja está bloqueada por el administrador.'])->withInput();
        }

        // Regla: edición permitida solo si faltan >= 24 horas para la clase.
        $originalDT = \Carbon\Carbon::parse("{$booking->class_date} {$booking->class_time}");
        if (now()->diffInHours($originalDT, false) < 24) {
            return back()->withErrors(['general' => 'No puedes editar la reserva con menos de 24 horas de antelación.'])->withInput();
        }

        // La nueva fecha/hora debe ser futura.
        $newDT = \Carbon\Carbon::parse($data['class_date'] . ' ' . substr($data['class_time'], 0, 5));
        if ($newDT->isPast()) {
            return back()->withErrors(['class_time' => 'Selecciona una fecha y hora futuras.'])->withInput();
        }

        // Límite de ediciones por reserva: máximo 2.
        if (($booking->edit_count ?? 0) >= 2) {
            return back()->withErrors(['general' => 'Has alcanzado el límite de ediciones para esta reserva.'])->withInput();
        }

        // Actualizar la reserva y incrementar contador de ediciones.
        $booking->update($data);
        $booking->increment('edit_count');

        // Notificar al usuario y al admin del cambio.
        try {
            if ($booking->user) {
                $booking->user->notify(new BookingUpdatedNotification($booking));
            } else {
                Notification::route('mail', $booking->email)
                    ->notify(new BookingUpdatedNotification($booking));
            }

            Notification::route('mail', env('ADMIN_EMAIL', config('mail.from.address')))
                ->notify(new BookingAdminUpdatedNotification($booking));
        } catch (\Throwable $e) {
            // Silenciar errores de envío de notificaciones.
        }

        // Redirigir a vista de éxito.
        return redirect()->route('user.bookings.edit_success')
            ->with('ok', 'Reserva actualizada correctamente.');
    }

    // Vista de éxito tras editar una reserva.
    public function editSuccess()
    {
        return view('user.bookings.edit_success');
    }

    // Cancela la reserva con lógica de reembolso si aplica (>=24h y pagada).
    public function destroy(ClassBooking $booking)
    {
        $this->authorizeBooking($booking);

        // Calcular horas restantes hasta la clase.
        $classDateTime = \Carbon\Carbon::parse($booking->class_date . ' ' . substr($booking->class_time, 0, 5));
        $now = now();
        $hoursUntil = $now->diffInHours($classDateTime, false);

        // Determinar si es reembolsable: >=24h, pagada y con payment_intent.
        $isRefundable = ($hoursUntil >= 24) && ($booking->paid === true) && !empty($booking->payment_intent);

        if ($isRefundable) {
            // Intentar reembolso automático vía Stripe.
            try {
                $stripe = new StripeClient(config('services.stripe.secret'));

                $refund = $stripe->refunds->create([
                    'payment_intent' => $booking->payment_intent,
                    'reason' => 'requested_by_customer',
                ]);

                // Actualizar la reserva en BD con datos del reembolso.
                $booking->refunded = true;
                $booking->refund_id = $refund->id;
                $booking->refunded_at = now();
                $booking->status = 'cancelled';
                $booking->save();

                // Notificar al usuario y al admin de la cancelación con reembolso.
                try {
                    if ($booking->user) {
                        $booking->user->notify(new BookingCancelledByUserRefundableNotification($booking));
                    } else {
                        Notification::route('mail', $booking->email)->notify(new BookingCancelledByUserRefundableNotification($booking));
                    }
                } catch (\Throwable $e) {
                    // Silenciar errores de notificación.
                }

                // Notificar al admin.
                try {
                    Notification::route('mail', env('ADMIN_EMAIL', config('mail.from.address')))
                        ->notify(new BookingAdminCancelledNotification($booking));
                } catch (\Throwable $e) {
                    // Silenciar.
                }

                return redirect()->route('user.bookings.index')->with('ok', 'Reserva cancelada y reembolsada.');
            } catch (\Throwable $e) {
                // Si el reembolso falla, informar al usuario sin exponer detalles técnicos.
                return back()->with('error', 'No se pudo procesar el reembolso. Contacta con soporte.');
            }
        }

        // Caso no reembolsable: marcar como cancelada y notificar.
        $booking->update(['status' => 'cancelled']);

        try {
            if ($booking->user) {
                $booking->user->notify(new BookingCancelledNonRefundableNotification($booking));
            } else {
                Notification::route('mail', $booking->email)->notify(new BookingCancelledNonRefundableNotification($booking));
            }
        } catch (\Throwable $e) {
            // Silenciar errores de notificación.
        }

        try {
            Notification::route('mail', env('ADMIN_EMAIL', config('mail.from.address')))
                ->notify(new BookingAdminCancelledNotification($booking));
        } catch (\Throwable $e) {
            // Silenciar.
        }

        return redirect()->route('user.bookings.index')->with('ok', 'Reserva cancelada. En breves recibiras un email con los detalles de la cancelación.');
    }

    // Método protegido para autorizar que la reserva pertenezca al usuario actual.
    protected function authorizeBooking(ClassBooking $booking)
    {
        $user = Auth::user();
        if ($booking->user_id !== $user->id) {
            abort(403); // Acceso denegado.
        }
    }
}
