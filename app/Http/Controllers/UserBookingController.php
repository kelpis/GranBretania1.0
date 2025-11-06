<?php

namespace App\Http\Controllers;

use App\Models\ClassBooking;
use App\Models\TranslationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Stripe\StripeClient;
use App\Notifications\BookingCancelledNonRefundableNotification;
use App\Notifications\BookingCancelledByUserRefundableNotification;
use App\Notifications\BookingUpdatedNotification;
use App\Notifications\BookingAdminUpdatedNotification;
use App\Notifications\BookingCancelledNotification;
use App\Notifications\BookingAdminCancelledNotification;
use App\Models\AvailabilitySlot;

class UserBookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Lista las reservas del usuario (filtradas por email)
    public function index()
    {
        $user = Auth::user();
        $bookings = ClassBooking::where('user_id', $user->id)
            ->orderBy('class_date', 'desc')
            ->orderBy('class_time', 'desc')
            ->get();

        // Obtener solicitudes de traducción asociadas al email del usuario
        $translations = TranslationRequest::where('email', $user->email)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.bookings.index', compact('bookings', 'translations'));
    }

    // Formulario para editar una reserva (si le pertenece)
    public function edit(ClassBooking $booking)
    {
        $this->authorizeBooking($booking);

        return view('user.bookings.edit', compact('booking'));
    }

    // Actualiza la reserva (solo campos permitidos)
    public function update(Request $request, ClassBooking $booking)
    {
        $this->authorizeBooking($booking);

        $data = $request->validate([
            'class_date' => 'required|date',
            'class_time' => 'required',
            'name' => 'required|string|max:255',
            // phone: allow digits, spaces, +, parentheses and hyphens
            'phone' => ['nullable', 'string', 'max:50', 'regex:/^[0-9+\\s\\-()]+$/'],
            'notes' => 'nullable|string|max:1000',
        ]);

        // Evitar colisiones: comprobar que no exista otra reserva (distinta) en la misma fecha/hora
        $conflict = ClassBooking::where('class_date', $data['class_date'])
            ->where('class_time', $data['class_time'])
            ->where('id', '!=', $booking->id)
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->exists();

        if ($conflict) {
            return back()->withErrors(['class_time' => 'Esa franja ya está ocupada.'])->withInput();
        }

        // Comprobar si la franja está bloqueada por admin
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

        $booking->update($data);

        // Notificar al usuario y al admin del cambio (proteger contra errores de envío)
        try {
            Notification::route('mail', $booking->email)
                ->notify(new BookingUpdatedNotification($booking));

            Notification::route('mail', env('ADMIN_EMAIL', config('mail.from.address')))
                ->notify(new BookingAdminUpdatedNotification($booking));
        } catch (\Throwable $e) {
            // Silenciar errores de envío de notificaciones para no escribir en logs aquí
        }

        // Mostrar la vista de éxito tras la edición (ubicada en user/bookings) y permitir volver al dashboard
        return redirect()->route('user.bookings.edit_success')
            ->with('ok', 'Reserva actualizada correctamente.');
    }

    // Cancela (soft change status) la reserva. Si se cancela con >=24h de antelación y existe pago,
    // se intenta reembolsar automáticamente; en caso contrario se cancela sin reembolso.
    public function destroy(ClassBooking $booking)
    {
        $this->authorizeBooking($booking);

    // Construir fecha/hora de la clase y calcular horas restantes
    $classDateTime = \Carbon\Carbon::parse($booking->class_date . ' ' . substr($booking->class_time, 0, 5));
    $now = now();

    // diffInHours con absolute=false devuelve positivo si classDateTime está en el futuro
    $hoursUntil = $now->diffInHours($classDateTime, false);

    // Reembolsable si quedan 24 horas o más hasta la clase, y la reserva está pagada con payment_intent
    $isRefundable = ($hoursUntil >= 24) && ($booking->paid === true) && !empty($booking->payment_intent);

        if ($isRefundable) {
            // Intentar reembolso vía Stripe
            try {
                $stripe = new StripeClient(config('services.stripe.secret'));

                $refund = $stripe->refunds->create([
                    'payment_intent' => $booking->payment_intent,
                    'reason' => 'requested_by_customer',
                ]);

                // Actualizar BD
                $booking->refunded = true;
                $booking->refund_id = $refund->id;
                $booking->refunded_at = now();
                $booking->status = 'cancelled';
                $booking->save();

                // Notificar al usuario de la cancelación y reembolso (usuario la canceló)
                try {
                    if ($booking->user) {
                        $booking->user->notify(new BookingCancelledByUserRefundableNotification($booking));
                    } else {
                        Notification::route('mail', $booking->email)->notify(new BookingCancelledByUserRefundableNotification($booking));
                    }
                } catch (\Throwable $e) {
                    // Silenciar errores de notificación
                }

                // Notificar al admin
                try {
                    Notification::route('mail', env('ADMIN_EMAIL', config('mail.from.address')))
                        ->notify(new BookingAdminCancelledNotification($booking));
                } catch (\Throwable $e) {
                    // Silenciar
                }

                return redirect()->route('user.bookings.index')->with('ok', 'Reserva cancelada y reembolsada.');
            } catch (\Throwable $e) {
                // Si el reembolso falla, informar al usuario sin exponer detalles técnicos
                return back()->with('error', 'No se pudo procesar el reembolso. Contacta con soporte.');
            }
        }

        // No reembolsable: marcar cancelada y avisar con la notificación correspondiente
        $booking->update(['status' => 'cancelled']);

        try {
            if ($booking->user) {
                $booking->user->notify(new BookingCancelledNonRefundableNotification($booking));
            } else {
                Notification::route('mail', $booking->email)->notify(new BookingCancelledNonRefundableNotification($booking));
            }
        } catch (\Throwable $e) {
            // Silenciar errores de notificación
        }

        try {
            Notification::route('mail', env('ADMIN_EMAIL', config('mail.from.address')))
                ->notify(new BookingAdminCancelledNotification($booking));
        } catch (\Throwable $e) {
            // Silenciar
        }

        return redirect()->route('user.bookings.index')->with('ok', 'Reserva cancelada. No procede reembolso.');
    }

    protected function authorizeBooking(ClassBooking $booking)
    {
        $user = Auth::user();
        if ($booking->user_id !== $user->id) {
            abort(403);
        }
    }
}
