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


//CONTROLADOR PANEL DEL ADMINISTRADOR
class AdminController extends Controller
{
    public function index()
    {
        // Estadísticas básicas para el dashboard
        //Calcula la fecha de hoy con Carbon
        $today = Carbon::today()->toDateString();

        //Nº total de reservas en la BD
        $totalBookings = ClassBooking::count();
        //Reservas futuras (fecha ≥ hoy) que no están canceladas ni rechazadas.
        $upcomingBookings = ClassBooking::where('class_date', '>=', $today)
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->count();
        //Reservas de hoy que no están canceladas
        $todayClasses = ClassBooking::where('class_date', $today)
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->count();


        // Traducciones pendientes
        $pendingTranslations = TranslationRequest::count();

        //Agrupa las cifras anteriores para pasar a la vista
        $stats = [
            'total_bookings' => $totalBookings,
            'upcoming_bookings' => $upcomingBookings,
            'today_classes' => $todayClasses,
            'pending_translations' => $pendingTranslations,
        ];

        //Listados breves para mostrar en dashboard
        //Coge las próximas reservas (iguales o posteriores a hoy), sin canceladas ni rechazadas, ordenadas por fecha, máximo 8
        $nextBookings = ClassBooking::where('class_date', '>=', $today)
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->orderBy('class_date')
            ->limit(8)
            ->get();

        $recentTranslations = TranslationRequest::latest()->limit(8)->get();

        //Devuelve la vista con los datos
        return view('admin.dashboard', compact('stats', 'nextBookings', 'recentTranslations'));
    }



    //DEVOLVER PAGO Y CANCELAR RESERVA
    public function refund(ClassBooking $booking)
    {
        //Recibe una reserva ClassBooking por route model binding

        //Comprueba:
        //Que esté marcada como pagada (paid) y tenga payment_intent (el id de Stripe).
        if (! $booking->paid || ! $booking->payment_intent) {
            return back()->with('error', 'Esta reserva no tiene un pago válido para devolver.');
        }
        //Que no se haya reembolsado ya (refunded).
        if ($booking->refunded) {
            return back()->with('error', 'Esta reserva ya fue devuelta.');
        }


        //Crea una instancia de StripeClient usando la secret del .env
        try {
            $stripe = new StripeClient(config('services.stripe.secret'));

            //Llama a la API de Stripe para crear reembolso total de ese payment_intent
            //Guarda el objeto $refund devuelto por Stripe
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


            //Registra en log que el reembolso ha ido bien y vuelve a la página anterior con mensaje de éxito.
            Log::info('Stripe refund successful', [
                'booking_id' => $booking->id,
                'refund_id' => $refund->id,
            ]);
            return back()->with('success', 'Pago devuelto y reserva cancelada.');

            
        //Si falla la parte de Stripe (API, conexión, claves, etc.), lo registra como error y muestra un mensaje al admin.
        } catch (\Throwable $e) {
            Log::error('Stripe refund failed', ['booking_id' => $booking->id, 'error' => $e->getMessage()]);
            return back()->with('error', 'No se pudo procesar la devolución.');
        }
    }
}
