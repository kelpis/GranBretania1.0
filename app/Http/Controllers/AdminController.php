<?php

namespace App\Http\Controllers;
use Stripe\StripeClient;
use App\Http\Requests\StoreClassBookingRequest;
use App\Models\ClassBooking;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
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
