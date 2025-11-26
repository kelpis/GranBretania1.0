<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TranslationRequest extends Model
{
    //MODELO SOLICITUD TRADUCCIONES
    //Campos asignables al guardar o actualizar
    protected $fillable = [
        // 'name' and 'email' are legacy and will be removed once every request
        // is associated to a user via `user_id`. Prefer `user->name`/`user->email`.
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
      //Convertir campos al tipo necesario
    protected $casts = [
        'gdpr_given'   => 'boolean',
        'gdpr_at'      => 'datetime',
        'paid_at'      => 'datetime',
        'delivered_at' => 'datetime',
    ];
    // Relación  con usuario (user_id )
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
    //Método de ayuda para comprobar si la solicitud ha sido pagada.
    public function isPaid()    
    {
        return $this->status === 'paid';
    }
    //Método de ayuda para comprobar si la solicitud ha sido enviada.
    public function isDelivered()
    {
        return $this->status === 'delivered' && $this->output_file_path;
    }
}
