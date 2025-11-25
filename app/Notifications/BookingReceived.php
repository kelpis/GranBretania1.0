<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\ClassBooking;

class BookingReceived extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public ClassBooking $booking) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Tu clase ha sido reservada con éxito 🎉')
            ->greeting("Hola " . ($this->booking->user->name ?? $this->booking->name) . " 👋")
            ->line("Hemos recibido correctamente tu pago para la clase del " .
                \Carbon\Carbon::parse($this->booking->class_date)->format('d/m/Y') .
                " a las " . substr($this->booking->class_time, 0, 5) . ".")
            ->line('En breve recibirás otro correo con el enlace para acceder a la videollamada.')
            ->line('¡Gracias por confiar en nosotros para seguir mejorando tu inglés!')
            ->salutation('— El equipo de Gran Bretania');
    }
}
