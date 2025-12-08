<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ClassBooking;
use App\Notifications\BookingConfirmedNotification;
use App\Notifications\BookingCancelledNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon; 
//CONTROLADOR ADMIN RESERVAS CLASES
class BookingAdminController extends Controller
{
    public function index()
    {
        // Reservas pendientes
        $pendientes = ClassBooking::where('status', 'pending')
            ->where('paid', true)
            ->orderBy('class_date')
            ->orderBy('class_time')
            ->get();

        //Reservas confirmadas:SOLO FUTURAS
        $confirmadas = ClassBooking::where('status', 'confirmed')
            ->whereDate('class_date', '>=', Carbon::today())
            ->orderBy('class_date')
            ->orderBy('class_time')
            ->get();

        //Canceladas recientes:últimas 10, ordenadas de más nueva a más vieja
        $canceladas = ClassBooking::where('status', 'cancelled')
            ->orderBy('class_date', 'desc')
            ->orderBy('class_time', 'desc')
            ->limit(10)
            ->get();

        return view('admin.booking', compact('pendientes', 'confirmadas', 'canceladas'));
    }


    //Confirmar reservas
    public function confirm(ClassBooking $booking)
    {
        // Validar que el admin proporcione una URL de videollamada válida
        $validated = request()->validate([
            'meeting_url' => ['required', 'url'],
        ]);
        // Evitar solape: si ya hay otra confirmada misma fecha/hora
        $exists = ClassBooking::where('id', '!=', $booking->id)
            ->where('class_date', $booking->class_date)
            ->where('class_time', $booking->class_time)
            ->where('status', 'confirmed')
            ->exists();

        if ($exists) {
            return back()->with('error', 'Ya hay otra reserva confirmada en esa franja.');
        }

        // Actualizar estado y guardar la URL validada
        $booking->update([
            'status' => 'confirmed',
            'meeting_url' => $validated['meeting_url'],
        ]);

        // Asegurarnos de refrescar el modelo para que contenga meeting_url actualizado
        $booking->refresh();

        //Enviar notificacion con url de la meeting al usuario.
        try {
            if ($booking->user) {
                $booking->user->notify(new BookingConfirmedNotification($booking));
            } else {
                Notification::route('mail', $booking->email)
                    ->notify(new BookingConfirmedNotification($booking));
            }
        } catch (\Throwable $e) {
            Log::warning('Error al enviar confirmación: ' . $e->getMessage());
        }

        return back()->with('ok', 'Reserva confirmada y correo enviado.');
    }

    //Cancelar reserva

    public function cancel(ClassBooking $booking)
    {
        //Actualiza estado a cancelado
        $booking->update(['status' => 'cancelled']);

        try {
            if ($booking->user) {
                $booking->user->notify(new BookingCancelledNotification($booking));
            } else {
                Notification::route('mail', $booking->email)
                    ->notify(new BookingCancelledNotification($booking));
            }
        } catch (\Throwable $e) {
            Log::warning('Error al enviar cancelación: ' . $e->getMessage());
        }

        return back()->with('ok', 'Reserva cancelada y aviso enviado.');
    }
}
