<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AvailabilitySlot extends Model
{
    //CONTROL DE DISPONIBILIDAD DEL ADMIN
    /**
     * Campos asignables.
     * - date: fecha del slot (YYYY-MM-DD)
     * - start_time: hora de inicio (HH:MM:SS)
     * - end_time: hora de fin (HH:MM:SS)
     * - status: estado del slot (Bloqueado o disponible)
     *
     * @var array
     */
    protected $fillable = ['date', 'start_time', 'end_time', 'status'];

    /**
     * Relación: un AvailabilitySlot puede tener muchas reservas (ClassBooking).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(ClassBooking::class, 'availability_slot_id');
    }

    /**
     * Scope: devuelve sólo los slots marcados como 'available' y cuya fecha es hoy o futura.
     *mostrar solo horas disponibles al usuario
     *evitar reservas para días pasados
     *evitar mostrar horarios bloqueados
     * @param  \Illuminate\Database\Eloquent\Builder  $q
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnlyAvailable($q)
    {
        return $q->where('status', 'available')
            ->whereDate('date', '>=', now()->toDateString());
    }

    /**
     * Devuelve una etiqueta legible del slot con fecha y rango horario.
     * Formato resultante: "YYYY-MM-DD HH:MM–HH:MM".
     * Selects del formulario de reserva
     * @return string
     */
    public function label(): string
    {
        return sprintf('%s %s–%s', $this->date, substr($this->start_time, 0, 5), substr($this->end_time, 0, 5));
    }
}
