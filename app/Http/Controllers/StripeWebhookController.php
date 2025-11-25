<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Stripe\Webhook;
use App\Models\ClassBooking;
use App\Notifications\BookingReceived;
use App\Notifications\BookingAdminNotification;
use App\Models\TranslationRequest;


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

            // 🔹 PRIMERO: comprobar si es una traducción
            $metadata = $session->metadata ?? null;

            $translationId = null;
            $type = null;

            if ($metadata) {
                // metadata puede venir como objeto o como array
                $translationId = $metadata->translation_id ?? ($metadata['translation_id'] ?? null);
                $type          = $metadata->type ?? ($metadata['type'] ?? null);
            }

            if ($translationId && $type === 'translation') {
                $translation = TranslationRequest::find($translationId);

                if ($translation && ! $translation->paid_at) {
                    $translation->paid_at       = now();
                    $translation->payment_intent = $session->payment_intent ?? null;
                    $translation->status        = 'paid';
                    $translation->save();

                    Log::info('Stripe webhook: translation marked paid', [
                        'translation_id' => $translation->id,
                        'session_id'     => $session->id,
                    ]);

                    // Aquí más adelante puedes añadir notificaciones al usuario/admin si quieres.
                }

                // Como es una traducción, no seguimos con la lógica de reservas.
                return response()->json(['ok' => true]);
            }


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

            // 3️⃣ Si no hay booking tras buscar por session o metadata, no intentamos
            //     emparejar por email. Requerimos `stripe_session_id` o `metadata.booking_id`.
            if (! $booking) {
                Log::info('Stripe webhook: no class booking matched by stripe_session_id or metadata.booking_id', [
                    'session_id' => $session->id ?? null,
                    'metadata' => is_object($session->metadata) ? (array)$session->metadata : $session->metadata,
                ]);
                return response()->json(['ok' => true]);
            }

            // 4️⃣ Si encontramos la reserva y aún no estaba pagada
            if ($booking && ! $booking->paid) {
                $booking->paid = true;
                $booking->paid_at = now();
                $booking->payment_intent = $session->payment_intent ?? null;
                $booking->amount_paid = $session->amount_total ?? null;
                $booking->currency = $session->currency ?? null;

                // Mantener la reserva en 'pending' tras el pago para que el admin
                // revise y confirme manualmente (a menos que estuviera cancelada).
                if ($booking->status !== 'cancelled') {
                    $booking->status = 'pending';
                }

                $booking->save();

                Log::info('Stripe webhook: booking marked paid', ['booking_id' => $booking->id]);

                // Enviar notificaciones: preferimos notificar a través del modelo User
                try {
                    if ($booking->user) {
                        $booking->user->notify(new BookingReceived($booking));
                    } else {
                        $recipient = $booking->email;
                        Notification::route('mail', $recipient)
                            ->notify(new BookingReceived($booking));
                    }

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

            if (isset($obj->metadata) && !empty($obj->metadata)) {
                $bookingId = $obj->metadata->booking_id ?? ($obj->metadata['booking_id'] ?? null);
            }

            Log::info('Stripe payment/charge event received', [
                'type' => $event->type,
                'booking_id_meta' => $bookingId,
                'object_id' => $obj->id ?? null,
            ]);

            // Buscar booking solo por metadata.booking_id (no por email)
            $booking = null;
            if ($bookingId) {
                $booking = ClassBooking::find($bookingId);
            }

            if (! $booking) {
                Log::info('Stripe payment/charge event: no booking matched by metadata.booking_id', [
                    'object_id' => $obj->id ?? null,
                    'booking_id_meta' => $bookingId,
                ]);
                return response()->json(['ok' => true]);
            }

            if ($booking && ! $booking->paid) {
                $booking->paid = true;
                $booking->paid_at = now();
                $booking->payment_intent = $obj->id ?? ($obj->payment_intent ?? null);
                $booking->amount_paid = $obj->amount ?? ($obj->amount_received ?? null);
                $booking->currency = $obj->currency ?? null;

                // Mantener en 'pending' para que el admin confirme la clase y ponga
                // la URL de la videollamada.
                if ($booking->status !== 'cancelled') {
                    $booking->status = 'pending';
                }

                $booking->save();

                Log::info('Stripe webhook (payment/charge): booking marked paid', [
                    'booking_id' => $booking->id,
                    'event_type' => $event->type,
                ]);

                // Notificaciones: preferimos notificar a través del modelo User
                try {
                    if ($booking->user) {
                        $booking->user->notify(new BookingReceived($booking));
                    } else {
                        $recipient = $booking->email;
                        Notification::route('mail', $recipient)
                            ->notify(new BookingReceived($booking));
                    }

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
