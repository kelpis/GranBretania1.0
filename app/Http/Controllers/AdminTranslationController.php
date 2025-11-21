<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TranslationPaymentLink;


use App\Models\TranslationRequest;
use Illuminate\Http\Request;
use Stripe\StripeClient;
use Illuminate\Support\Facades\Log;

class AdminTranslationController extends Controller
{
    /**
     * Asigna un precio final a la traducción y crea una sesión de Stripe Checkout.
     * Devuelve un enlace de pago que la admin puede copiar y enviar al cliente.
     */
    public function quote(Request $request, TranslationRequest $translation)
{
    // Evitar presupuestar algo ya pagado o entregado
    if (in_array($translation->status, ['paid', 'delivered'])) {
        return back()->with('error', 'Esta traducción ya está pagada o entregada.');
    }

    $data = $request->validate([
        'amount_eur' => ['required', 'numeric', 'min:1'],
    ]);

    $amountEur = $data['amount_eur'];

    // Guardar precio en céntimos
    $translation->final_price_cents = (int) round($amountEur * 100);
    if (empty($translation->currency)) {
        $translation->currency = 'eur';
    }
    $translation->status = 'quoted';
    $translation->save();

    // Stripe
    $stripeSecret = config('services.stripe.secret');

    if (empty($stripeSecret)) {
        return back()->with('error', 'Stripe no está configurado. Revisa services.stripe.secret.');
    }

    try {
        $stripe = new StripeClient($stripeSecret);

        $session = $stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'mode' => 'payment',
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
            'customer_email' => $translation->email,
            'metadata' => [
                'translation_id' => $translation->id,
                'type' => 'translation',
            ],
            'success_url' => route('user.translations.index') . '?t_paid={CHECKOUT_SESSION_ID}',
            'cancel_url'  => route('user.translations.index'),
        ]);

        // Guardar id de sesión
        $translation->stripe_session_id = $session->id;
        $translation->save();


        /* ============================================================
           📩  ENVIAR EMAIL AUTOMÁTICO CON EL ENLACE DE PAGO STRIPE
           ============================================================ */

        Notification::route('mail', $translation->email)
            ->notify(new TranslationPaymentLink(
                $translation,
                $session->url
            ));

        /* ========================================================== */


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



    /**
     * Admin sube el archivo traducido y marca la traducción como entregada.
     */
    public function deliver(Request $request, TranslationRequest $translation)
    {
        $request->validate([
            'output_file' => ['required', 'file', 'max:20480'], // 20MB
        ]);

        // Guardar archivo traducido final
        $path = $request->file('output_file')->store('translations-output');

        $translation->output_file_path = $path;
        $translation->delivered_at = now();
        $translation->status = 'delivered';
        $translation->save();

        return back()->with('ok', 'Traducción final subida y marcada como entregada.');
    }
}
