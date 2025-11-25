<?php

namespace App\Notifications;

use App\Models\TranslationRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TranslationDelivered extends Notification
{
    use Queueable;

    public function __construct(public TranslationRequest $tr)
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Tu traducción está lista · Gran Bretania')
            ->greeting("Hola " . ($this->tr->user->name ?? $this->tr->name) . " 👋")
            ->line('¡Buenas noticias! Tu traducción ya ha sido completada.')
            ->line('Ya puedes descargar el documento final desde tu panel de usuario.')
            ->action('Descargar traducción', route('user.translations.index'))
            ->line('Gracias por confiar en Gran Bretania para tu traducción. Si necesitas más ayuda, estamos aquí para ayudarte.')
            ->salutation("Un saludo,\nEl equipo de Gran Bretania");
    }
}
