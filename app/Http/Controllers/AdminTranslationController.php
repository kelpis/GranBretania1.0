<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Notification;
use App\Notifications\TranslationPaymentLink;
use App\Notifications\TranslationDelivered;


use App\Models\TranslationRequest;
use Illuminate\Http\Request;
use Stripe\StripeClient;
use Illuminate\Support\Facades\Log;
//CONTRALADOR ACCIONES TRANDUCCIONES ADMINISTRADOR
class AdminTranslationController extends Controller
{

    // Asigna un precio final a la traducción y crea una sesión de Stripe Checkout.
    //Devuelve un enlace de pago que la admin puede copiar y enviar al cliente.

    public function quote(Request $request, TranslationRequest $translation)
    {
        // Evitar presupuestar algo ya pagado o entregado
        if (in_array($translation->status, ['paid', 'delivered'])) {
            return back()->with('error', 'Esta traducción ya está pagada o entregada.');
        }
        //Valida formulario, precio presente,numerico y mayor que 1
        $data = $request->validate([
            'amount_eur' => ['required', 'numeric', 'min:1'],
        ]);


        $amountEur = $data['amount_eur'];

        // Guardar precio en céntimos (requisito Stripe)
        $translation->final_price_cents = (int) round($amountEur * 100);
        //Por defecto la moneda es Euro
        if (empty($translation->currency)) {
            $translation->currency = 'eur';
        }
        //Cambia el estado quoted y guarda en BD.
        $translation->status = 'quoted';
        $translation->save();

        //Crea una instancia de StripeClient usando la secret del .env
        $stripeSecret = config('services.stripe.secret');

        if (empty($stripeSecret)) {
            return back()->with('error', 'Stripe no está configurado. Revisa services.stripe.secret.');
        }
        //Genera una sesión de pago para el cliente con:
        //Método de pago: tarjeta
        //Modo de pago ⇒ pago único
        try {
            $stripe = new StripeClient($stripeSecret);

            $recipientEmail = $translation->user->email ?? $translation->email;

            $session = $stripe->checkout->sessions->create([
                'payment_method_types' => ['card'],
                'mode' => 'payment',

                //Stripe necesita:Nombre del servicio,precio unitario,cantidad
                'line_items' => [[
                    'price_data' => [
                        'currency' => $translation->currency,
                        'product_data' => [
                            'name' => 'Traducción #' . $translation->id,
                        ],
                        'unit_amount' => $translation->final_price_cents,
                    ],
                    'quantity' => 1,
                ]],

                //El email se usa para que Stripe notifique,metadatos útiles para webhooks.
                'customer_email' => $recipientEmail,
                'metadata' => [
                    'translation_id' => $translation->id,
                    'type' => 'translation',
                ],
                //El sistema redirige al panel del usuario y añade un parámetro para identificar la sesión pagada
                'success_url' => route('user.translations.index') . '?t_paid={CHECKOUT_SESSION_ID}',
                'cancel_url'  => route('user.translations.index'),
            ]);

            // Guardar id de sesión
            $translation->stripe_session_id = $session->id;
            $translation->save();



            //ENVIAR EMAIL AUTOMÁTICO CON EL ENLACE DE PAGO STRIPE
            //Preferir notificar usando el modelo User (si existe) para que
            //la notificación use sus canales/configuración y mantenga la relación.
            if ($translation->user) {
                $translation->user->notify(new TranslationPaymentLink($translation, $session->url));
            } else {
                Notification::route('mail', $recipientEmail)
                    ->notify(new TranslationPaymentLink(
                        $translation,
                        $session->url
                    ));
            }

            return back()
                ->with('ok', 'Presupuesto guardado y enlace de pago generado.')
                ->with('translation_payment_url', $session->url)
                ->with('translation_payment_id', $translation->id);
        } catch (\Throwable $e) {
            Log::error('Error creando sesión Stripe para traducción', [
                'translation_id' => $translation->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'No se pudo crear el enlace de pago en Stripe.');
        }
    }



    //Admin sube el archivo traducido y marca la traducción como entregada.
    
    public function deliver(Request $request, TranslationRequest $translation)
    {
        //Valida formato y peso del documento
        $request->validate([
            'output_file' => ['required', 'file', 'max:20480'], // 20MB
        ]);

        // Guardar archivo traducido final,fecha de entrega, cambia estado a delivered y guarda
        $path = $request->file('output_file')->store('translations-output');

        $translation->output_file_path = $path;
        $translation->delivered_at = now();
        $translation->status = 'delivered';
        $translation->save();

        //Enviar email al usuario: preferimos notificar a través del modelo User
        //para usar sus canales/colas; si no hay user, usamos el email almacenado.
        $recipient = $translation->user->email ?? $translation->email;
        if ($translation->user) {
            $translation->user->notify(new TranslationDelivered($translation));
        } else {
            Notification::route('mail', $recipient)
                ->notify(new TranslationDelivered($translation));
        }

        return back()->with('ok', 'Traducción final subida y marcada como entregada.');
    }
}
