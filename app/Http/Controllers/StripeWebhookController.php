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
        } catch (\UnexpectedValueException $e) {
            Log::warning('Stripe webhook invalid payload: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::warning('Stripe webhook invalid signature: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $bookingId = $session->metadata->booking_id ?? null;

            if ($bookingId) {
                $booking = ClassBooking::find($bookingId);
                if ($booking && ! $booking->paid) {
                    $booking->paid = true;
                    $booking->paid_at = now();
                    $booking->payment_intent = $session->payment_intent ?? null;
                    $booking->amount_paid = $session->amount_total ?? null;
                    $booking->currency = $session->currency ?? null;
                    $booking->save();

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
