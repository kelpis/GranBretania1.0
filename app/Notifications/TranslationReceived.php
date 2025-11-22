<?php

namespace App\Notifications;

use App\Models\TranslationRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TranslationReceived extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public TranslationRequest $tr)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Hemos recibido tu solicitud de traducción')
            ->greeting("¡Gracias por tu solicitud, {$this->tr->name}!")
            ->line('Tu traducción ya está en proceso de revisión inicial.')
            ->line("Idiomas: {$this->tr->source_lang} → {$this->tr->target_lang}")
            ->line('Urgencia: ' . ucfirst($this->tr->urgency ?? 'normal'))
            ->line('En breve revisaremos tu documento y te enviaremos un presupuesto personalizado junto con el enlace para realizar el pago de manera segura.')
            ->line('Si necesitamos algún dato adicional, nos pondremos en contacto contigo.')
            ->line('Gracias por confiar en Gran Bretania para tus traducciones.')
            ->salutation("Un saludo,\nEl equipo de Gran Bretania");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
