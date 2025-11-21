<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TranslationRequest extends Model
{
    protected $fillable = [
        'name',
        'email',
        'source_lang',
        'target_lang',
        'urgency',
        'file_path',
        'comments',
        'gdpr_given',
        'gdpr_at',
        'user_id',
        'status',
        'final_price_cents',
        'currency',
        'stripe_session_id',
        'payment_intent',
        'paid_at',
        'output_file_path',
        'delivered_at',
    ];

    protected $casts = [
        'gdpr_given'   => 'boolean',
        'gdpr_at'      => 'datetime',
        'paid_at'      => 'datetime',
        'delivered_at' => 'datetime',
    ];
    // Relación opcional con usuario (user_id nullable)
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function isPaid()    
    {
        return $this->status === 'paid';
    }

    public function isDelivered()
    {
        return $this->status === 'delivered' && $this->output_file_path;
    }
}
