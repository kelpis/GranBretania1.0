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
}
