<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Stripe\Webhook;
use App\Models\ClassBooking;
use App\Notifications\BookingReceived;
use App\Notifications\BookingAdminNotification;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload       = $request->getContent();
        $sigHeader     = $request->header('Stripe-Signature');
        $endpointSecret = env('STRIPE_WEBHOOK_SECRET');

        // ⚠️ Aviso si la secret no está configurada o no tiene el formato esperado
        try {
            if (empty($endpointSecret) || !str_starts_with($endpointSecret, 'whsec_')) {
                Log::warning('STRIPE_WEBHOOK_SECRET appears missing or unusual', [
                    'present' => !empty($endpointSecret)
                ]);
            }
        } catch (\Throwable $e) {
            // ignorar
        }

        // 🧾 Verificar firma del webhook
        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
            Log::info('Stripe webhook constructed', [
                'type' => $event->type ?? null,
                'stripe-signature' => $sigHeader,
            ]);
        } catch (\UnexpectedValueException $e) {
            Log::warning('Stripe webhook invalid payload: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::warning('Stripe webhook invalid signature: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        /* ===========================================================
         *  ✅ CASO PRINCIPAL: checkout.session.completed
         * =========================================================== */
        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            // 1️⃣ Buscar por session guardada (más fiable)
            $booking = ClassBooking::where('stripe_session_id', $session->id)->first();

            // 2️⃣ Si no existe, intentar por metadata.booking_id
            if (! $booking) {
                $metadata = $session->metadata ?? null;
                $bookingId = $metadata->booking_id ?? ($metadata['booking_id'] ?? null);

                if ($bookingId) {
                    $booking = ClassBooking::find($bookingId);
                    if (! $booking) {
                        Log::warning('Stripe webhook: booking not found by id', ['booking_id' => $bookingId]);
                    }
                }
            }

            // 3️⃣ Fallback: buscar por email reciente si no hay metadata
            if (! $booking) {
                $customerEmail = $session->customer_email ?? ($session->customer_details->email ?? null);
                if ($customerEmail) {
                    $booking = ClassBooking::where('email', $customerEmail)
                        ->where('paid', false)
                        ->where('created_at', '>=', now()->subHours(48))
                        ->orderBy('created_at', 'desc')
                        ->first();

                    if ($booking) {
                        Log::info('Stripe webhook fallback matched booking by email', [
                            'customer_email' => $customerEmail,
                            'matched_booking_id' => $booking->id,
                        ]);
                    } else {
                        Log::info('Stripe webhook fallback found no booking for email', [
                            'customer_email' => $customerEmail,
                        ]);
                    }
                }
            }

            // 4️⃣ Si encontramos la reserva y aún no estaba pagada
            if ($booking && ! $booking->paid) {
                $booking->paid = true;
                $booking->paid_at = now();
                $booking->payment_intent = $session->payment_intent ?? null;
                $booking->amount_paid = $session->amount_total ?? null;
                $booking->currency = $session->currency ?? null;

                // Opcional: actualizar estado
                if ($booking->status !== 'cancelled') {
                    $booking->status = 'confirmed';
                }

                $booking->save();

                Log::info('Stripe webhook: booking marked paid', ['booking_id' => $booking->id]);

                // Enviar notificaciones
                try {
                    Notification::route('mail', $booking->email)
                        ->notify(new BookingReceived($booking));

                    sleep(1); // pequeña pausa entre correos

                    Notification::route('mail', env('ADMIN_EMAIL', config('mail.from.address')))
                        ->notify(new BookingAdminNotification($booking));
                } catch (\Throwable $e) {
                    Log::warning('Error sending booking notifications after Stripe webhook: ' . $e->getMessage());
                }
            }
        }

        /* ===========================================================
         *  🔄 EVENTOS SECUNDARIOS (backup): payment_intent / charge
         * =========================================================== */
        if (in_array($event->type, ['payment_intent.succeeded', 'charge.succeeded', 'charge.updated'])) {
            $obj = $event->data->object;
            $bookingId = null;
            $customerEmail = null;

            if (isset($obj->metadata) && !empty($obj->metadata)) {
                $bookingId = $obj->metadata->booking_id ?? ($obj->metadata['booking_id'] ?? null);
            }

            if (isset($obj->receipt_email)) {
                $customerEmail = $obj->receipt_email;
            }

            if (isset($obj->charges->data[0])) {
                $c = $obj->charges->data[0];
                $customerEmail = $customerEmail ?? ($c->billing_details->email ?? ($c->receipt_email ?? null));
            }

            Log::info('Stripe payment/charge event received', [
                'type' => $event->type,
                'booking_id_meta' => $bookingId,
                'customer_email' => $customerEmail,
                'object_id' => $obj->id ?? null,
            ]);

            // Buscar booking por id o email
            $booking = null;
            if ($bookingId) {
                $booking = ClassBooking::find($bookingId);
            }

            if (! $booking && $customerEmail) {
                $booking = ClassBooking::where('email', $customerEmail)
                    ->where('paid', false)
                    ->where('created_at', '>=', now()->subHours(48))
                    ->orderBy('created_at', 'desc')
                    ->first();
            }

            if ($booking && ! $booking->paid) {
                $booking->paid = true;
                $booking->paid_at = now();
                $booking->payment_intent = $obj->id ?? ($obj->payment_intent ?? null);
                $booking->amount_paid = $obj->amount ?? ($obj->amount_received ?? null);
                $booking->currency = $obj->currency ?? null;

                if ($booking->status !== 'cancelled') {
                    $booking->status = 'confirmed';
                }

                $booking->save();

                Log::info('Stripe webhook (payment/charge): booking marked paid', [
                    'booking_id' => $booking->id,
                    'event_type' => $event->type,
                ]);

                // Notificaciones
                try {
                    Notification::route('mail', $booking->email)
                        ->notify(new BookingReceived($booking));

                    sleep(1);

                    Notification::route('mail', env('ADMIN_EMAIL', config('mail.from.address')))
                        ->notify(new BookingAdminNotification($booking));
                } catch (\Throwable $e) {
                    Log::warning('Error sending notifications (payment/charge): ' . $e->getMessage());
                }
            }
        }

        return response()->json(['ok' => true]);
    }
}
