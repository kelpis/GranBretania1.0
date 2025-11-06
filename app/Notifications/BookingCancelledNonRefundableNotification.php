<?php

namespace App\Notifications;

use App\Models\ClassBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BookingCancelledNonRefundableNotification extends Notification
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
            ->line('Tu reserva ha sido cancelada.')
            ->line('De acuerdo con nuestras condiciones, las cancelaciones realizadas con menos de 24 horas de antelación no dan derecho a reembolso.')
            ->line('Si crees que hay un error o necesitas asistencia, por favor contacta con nosotros.')
            ->salutation('— El equipo de Gran Bretania');
    }
}
