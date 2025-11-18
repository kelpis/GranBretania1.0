<?php

namespace App\Http\Controllers;
use Stripe\StripeClient;
use App\Http\Requests\StoreClassBookingRequest;
use App\Models\ClassBooking;
use App\Models\TranslationRequest;
use Illuminate\Support\Facades\Log;
use App\Notifications\BookingCancelledNotification;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        // Estadísticas básicas para el dashboard
        $today = Carbon::today()->toDateString();

        $totalBookings = ClassBooking::count();
        $upcomingBookings = ClassBooking::where('class_date', '>=', $today)
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->count();

        $todayClasses = ClassBooking::where('class_date', $today)
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->count();

        // Traducciones pendientes: en este esquema no hay columna 'status',
        // asumimos que todas las solicitudes son pendientes hasta procesarse.
        $pendingTranslations = TranslationRequest::count();

        $stats = [
            'total_bookings' => $totalBookings,
            'upcoming_bookings' => $upcomingBookings,
            'today_classes' => $todayClasses,
            'pending_translations' => $pendingTranslations,
        ];

        // Listados breves para mostrar en dashboard
        $nextBookings = ClassBooking::where('class_date', '>=', $today)
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->orderBy('class_date')
            ->limit(8)
            ->get();

        $recentTranslations = TranslationRequest::latest()->limit(8)->get();

        return view('admin.dashboard', compact('stats', 'nextBookings', 'recentTranslations'));
    }

    public function refund(ClassBooking $booking)
    {
        if (! $booking->paid || ! $booking->payment_intent) {
            return back()->with('error', 'Esta reserva no tiene un pago válido para devolver.');
        }

        if ($booking->refunded) {
            return back()->with('error', 'Esta reserva ya fue devuelta.');
        }

        try {
            $stripe = new StripeClient(config('services.stripe.secret'));

            // Crear reembolso total
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

            // Enviar notificación al usuario informándole de la cancelación y reembolso
            try {
                if ($booking->user) {
                    $booking->user->notify(new BookingCancelledNotification($booking));
                } else {
                    // Si no hay relación con user, envía por email usando la dirección almacenada
                    Notification::route('mail', $booking->email)->notify(new BookingCancelledNotification($booking));
                }

                Log::info('Booking cancelled notification sent', [
                    'booking_id' => $booking->id,
                    'user_id' => $booking->user?->id ?? null,
                ]);
            } catch (\Throwable $e) {
                // No bloqueamos el flujo por un fallo en la notificación; lo registramos para revisión
                Log::error('Failed to send booking cancelled notification', ['booking_id' => $booking->id, 'error' => $e->getMessage()]);
            }

            Log::info('Stripe refund successful', [
                'booking_id' => $booking->id,
                'refund_id' => $refund->id,
            ]);

            return back()->with('success', 'Pago devuelto y reserva cancelada.');
        } catch (\Throwable $e) {
            Log::error('Stripe refund failed', ['booking_id' => $booking->id, 'error' => $e->getMessage()]);
            return back()->with('error', 'No se pudo procesar la devolución.');
        }
    }
}
