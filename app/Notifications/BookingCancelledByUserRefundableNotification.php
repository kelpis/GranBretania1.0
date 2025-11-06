<?php

namespace App\Notifications;

use App\Models\ClassBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BookingCancelledByUserRefundableNotification extends Notification
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
            ->subject('Reserva cancelada y reembolsada')
            ->greeting("Hola {$this->booking->name}")
            ->line('Has cancelado tu reserva y hemos procedido a solicitar el reembolso del importe abonado.')
            ->line('El reintegro aparecerá en tu cuenta en unos días, según tu entidad bancaria.')
            ->line('Si necesitas ayuda o quieres reservar otra fecha, contáctanos.')
            ->salutation('— El equipo de Gran Bretania');
    }
}
