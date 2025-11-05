<?php

namespace App\Notifications;

use App\Models\ClassBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BookingCancelledNotification extends Notification
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
            ->subject('Reserva cancelada')
            ->greeting("Hola {$this->booking->name}")
            ->line('Lamentamos informarte de que tu reserva ha sido cancelada.')
            ->line('Se ha procedido a realizar el reembolso del importe abonado. El reintegro aparecerá en tu cuenta en unos días, según tu entidad bancaria.')
            ->line('Si lo deseas, puedes volver a reservar otra fecha desde nuestra web.')
            ->salutation('— El equipo de Gran Bretania');
    }
}
