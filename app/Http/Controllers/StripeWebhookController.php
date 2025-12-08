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

// CONTROLADOR webhooks de Stripe
//Procesa pagos de reservas de clases y solicitudes de traducción.


class StripeWebhookController extends Controller
{
    //Recibe y procesa los webhooks de Stripe.
    //Verifica la autenticidad del webhook, identifica el tipo de evento y actualiza los registros correspondientes.
    public function handle(Request $request)
    {
        // Obtener el contenido del payload, la firma de Stripe y la clave secreta del webhook desde el entorno.
        $payload       = $request->getContent();
        $sigHeader     = $request->header('Stripe-Signature');
        $endpointSecret = env('STRIPE_WEBHOOK_SECRET');

        //Verificar si la clave secreta del webhook está configurada correctamente.
        //Si no está presente o no tiene el formato CORRECTO, registra una advertencia en los logs.
        try {
            if (empty($endpointSecret) || !str_starts_with($endpointSecret, 'whsec_')) {
                Log::warning('STRIPE_WEBHOOK_SECRET appears missing or unusual', [
                    'present' => !empty($endpointSecret)
                ]);
            }
        } catch (\Throwable $e) {
            //Ignorar errores en esta verificación para no interrumpir el flujo.
        }

        // Verificar la firma del webhook para asegurar que proviene de Stripe y no ha sido alterado.
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

        //Evento principal: cuando una sesión de checkout se completa exitosamente en Stripe.
        //Este evento indica que el pago ha sido procesado y podemos actualizar el estado de la reserva o traducción.
        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            //Verificar si el pago corresponde a una solicitud de traducción.
            //Las traducciones se identifican por metadata específica en la sesión de Stripe.
            $metadata = $session->metadata ?? null;

            $translationId = null;
            $type = null;

            if ($metadata) {
                //Extraer el ID de traducción y el tipo desde metadata (puede ser objeto o array).
                $translationId = $metadata->translation_id ?? ($metadata['translation_id'] ?? null);
                $type          = $metadata->type ?? ($metadata['type'] ?? null);
            }

            //Si es una traducción pagada, actualizar su estado y marcar como pagada.
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

                    
                }

                //Finalizar el procesamiento ya que es una traducción, no una reserva de clase.
                return response()->json(['ok' => true]);
            }


            //Si no es traducción, buscar la reserva de clase correspondiente.
            //Primero, intentar encontrar por el ID de sesión de Stripe guardado en la base de datos.
            $booking = ClassBooking::where('stripe_session_id', $session->id)->first();

            //Si no se encuentra por sesión, intentar por metadata.booking_id.
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

            
            // Se requiere estrictamente 'stripe_session_id' o 'metadata.booking_id' para evitar errores.
            if (! $booking) {
                Log::info('Stripe webhook: no class booking matched by stripe_session_id or metadata.booking_id', [
                    'session_id' => $session->id ?? null,
                    'metadata' => is_object($session->metadata) ? (array)$session->metadata : $session->metadata,
                ]);
                return response()->json(['ok' => true]);
            }

            //Si se encuentra la reserva y aún no ha sido marcada como pagada, actualizar su estado.
            if ($booking && ! $booking->paid) {
                $booking->paid = true;
                $booking->paid_at = now();
                $booking->payment_intent = $session->payment_intent ?? null;
                $booking->amount_paid = $session->amount_total ?? null;
                $booking->currency = $session->currency ?? null;

                //Mantener la reserva en 'pending' tras el pago para que el admin
                //revise y confirme manualmente (a menos que este cancelada).
                if ($booking->status !== 'cancelled') {
                    $booking->status = 'pending';
                }

                $booking->save();

                Log::info('Stripe webhook: booking marked paid', ['booking_id' => $booking->id]);

                //Enviar notificaciones al usuario y al administrador sobre el pago recibido.
                try {
                    if ($booking->user) {
                        //Notificar al usuario registrado.
                        $booking->user->notify(new BookingReceived($booking));
                    } else {
                        //Notificar por email si no hay usuario registrado.
                        $recipient = $booking->email;
                        Notification::route('mail', $recipient)
                            ->notify(new BookingReceived($booking));
                    }

                    sleep(1); // Pequeña pausa entre correos para evitar sobrecarga.

                    //Notificar al administrador.
                    Notification::route('mail', env('ADMIN_EMAIL', config('mail.from.address')))
                        ->notify(new BookingAdminNotification($booking));
                } catch (\Throwable $e) {
                    Log::warning('Error sending booking notifications after Stripe webhook: ' . $e->getMessage());
                }
            }
        }

        
        //Eventos secundarios (como respaldo): payment_intent.succeeded, charge.succeeded, charge.updated.
        //Estos eventos sirven como backup en caso de que checkout.session.completed no llegue o falle.
        if (in_array($event->type, ['payment_intent.succeeded', 'charge.succeeded', 'charge.updated'])) {
            $obj = $event->data->object;
            $bookingId = null;

            //Extraer el booking_id desde metadata del objeto.
            if (isset($obj->metadata) && !empty($obj->metadata)) {
                $bookingId = $obj->metadata->booking_id ?? ($obj->metadata['booking_id'] ?? null);
            }

            Log::info('Stripe payment/charge event received', [
                'type' => $event->type,
                'booking_id_meta' => $bookingId,
                'object_id' => $obj->id ?? null,
            ]);

            //Buscar la reserva solo por metadata.booking_id (no por sesión, ya que estos eventos no tienen sesión).
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

            //Si la reserva existe y no está pagada, marcar como pagada y actualizar detalles.
            if ($booking && ! $booking->paid) {
                $booking->paid = true;
                $booking->paid_at = now();
                $booking->payment_intent = $obj->id ?? ($obj->payment_intent ?? null);
                $booking->amount_paid = $obj->amount ?? ($obj->amount_received ?? null);
                $booking->currency = $obj->currency ?? null;

                //Mantener en 'pending' para que el administrador confirme la clase y proporcione la URL de la videollamada.
                if ($booking->status !== 'cancelled') {
                    $booking->status = 'pending';
                }

                $booking->save();

                Log::info('Stripe webhook (payment/charge): booking marked paid', [
                    'booking_id' => $booking->id,
                    'event_type' => $event->type,
                ]);

                //Enviar notificaciones al usuario y administrador.
                try {
                    if ($booking->user) {
                        //Notificar al usuario registrado.
                        $booking->user->notify(new BookingReceived($booking));
                    } else {
                        //Notificar por email si no hay usuario registrado.
                        $recipient = $booking->email;
                        Notification::route('mail', $recipient)
                            ->notify(new BookingReceived($booking));
                    }

                    sleep(1); // Pausa entre correos.

                    //Notificar al administrador.
                    Notification::route('mail', env('ADMIN_EMAIL', config('mail.from.address')))
                        ->notify(new BookingAdminNotification($booking));
                } catch (\Throwable $e) {
                    Log::warning('Error sending notifications (payment/charge): ' . $e->getMessage());
                }
            }
        }

        //Responder con éxito para confirmar recepción del webhook.
        return response()->json(['ok' => true]);
    }
}
