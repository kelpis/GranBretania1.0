<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ClassBooking extends Model
{
    protected $fillable = [
        'class_date',
        'class_time',
        'name',
        'email',
        'user_id',
        'phone',
        'notes',
        'status',
        'gdpr_given',
        'gdpr_at',
        'meeting_url',
        // payment fields
        'paid',
        'paid_at',
        'payment_intent',
        'amount_paid',
        'currency',
        'edit_count',
    ];

    protected $casts = [
        'gdpr_given' => 'boolean',
        'gdpr_at' => 'datetime',
        'paid' => 'boolean',
        'paid_at' => 'datetime',
        'edit_count' => 'integer',
    ];

    // Relación con el usuario (opcional)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Devuelve la etiqueta traducida del estado.
     * Uso en vistas: $booking->status_label
     */
    public function getStatusLabelAttribute(): string
    {
        $key = $this->status ?? 'unknown';

        // Intenta la traducción en el archivo resources/lang/{locale}/statuses.php
        $translation = __("statuses.$key");

        // Si no hay traducción (devuelve la clave sin traducir), devuelve un fallback bonito
        if ($translation === "statuses.$key") {
            return ucfirst(str_replace('_', ' ', $key));
        }

        return $translation;
    }
}
