<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Stripe\Webhook;
use Stripe\Stripe;
use App\Models\ClassBooking;
use App\Notifications\BookingReceived;
use App\Notifications\BookingAdminNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
            // Log básico para diagnóstico: evento recibido y headers principales
            try {
                Log::info('Stripe webhook constructed', [
                    'type' => $event->type ?? null,
                    'headers' => [
                        'stripe-signature' => $sigHeader,
                    ],
                ]);
            } catch (\Throwable $e) {
                // no bloquear el flujo si falla el logging
            }
        } catch (\UnexpectedValueException $e) {
            Log::warning('Stripe webhook invalid payload: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::warning('Stripe webhook invalid signature: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $metadata = $session->metadata ?? null;
            $bookingId = $metadata->booking_id ?? ($metadata['booking_id'] ?? null);

            // Registrar metadata recibida para depuración
            try {
                Log::info('Stripe checkout.session.completed received', [
                    'booking_id_meta' => $bookingId,
                    'metadata' => is_object($metadata) ? (array)$metadata : $metadata,
                    'session_id' => $session->id ?? null,
                ]);
            } catch (\Throwable $e) {
                // ignore logging errors
            }

            if ($bookingId) {
                $booking = ClassBooking::find($bookingId);
                if (! $booking) {
                    Log::warning('Stripe webhook: booking not found', ['booking_id' => $bookingId]);
                }

                if ($booking && ! $booking->paid) {
                    $booking->paid = true;
                    $booking->paid_at = now();
                    $booking->payment_intent = $session->payment_intent ?? null;
                    $booking->amount_paid = $session->amount_total ?? null;
                    $booking->currency = $session->currency ?? null;
                    $booking->save();

                    // Confirmar en logs que la reserva fue marcada
                    try {
                        Log::info('Stripe webhook: booking marked paid', ['booking_id' => $booking->id]);
                    } catch (\Throwable $e) {
                        // ignore
                    }

                    // Notificar al usuario y al admin
                    try {
                        Notification::route('mail', $booking->email)
                            ->notify(new BookingReceived($booking));

                        // pequeña pausa para proveedores de mail locales
                        sleep(1);

                        Notification::route('mail', env('ADMIN_EMAIL', config('mail.from.address')))
                            ->notify(new BookingAdminNotification($booking));
                    } catch (\Throwable $e) {
                        Log::warning('Error sending booking notifications after Stripe webhook: ' . $e->getMessage());
                    }
                }
            }
        }

        return response()->json(['ok' => true]);
    }
}
