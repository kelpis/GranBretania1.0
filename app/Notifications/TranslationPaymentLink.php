<?php

namespace App\Notifications;

use App\Models\TranslationRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TranslationPaymentLink extends Notification
{
    use Queueable;

    public function __construct(
        public TranslationRequest $translation,
        public string $checkoutUrl
    ) {}

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $amount = $this->translation->final_price_cents
            ? number_format($this->translation->final_price_cents / 100, 2, ',', '.') . ' €'
            : 'tu traducción';

        return (new MailMessage)
            ->subject('Presupuesto y enlace de pago · Gran Bretania')
            ->greeting("Hola " . ($this->translation->user->name ?? $this->translation->name) . " 👋")
            ->line("Ya tenemos el presupuesto para tu traducción. El importe total es de {$amount}.")
            ->line('Puedes completar el pago de forma segura a través de Stripe usando el siguiente botón:')
            ->action('Pagar traducción', $this->checkoutUrl)
            ->line('Una vez recibido el pago, comenzaremos con la traducción y te avisaremos cuando esté lista.')
            ->salutation('— El equipo de Gran Bretania');
    }
}
