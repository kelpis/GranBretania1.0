<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TranslationRequest extends Model
{
    protected $fillable = [
        'name','email','source_lang','target_lang','urgency','file_path','comments', 'gdpr_given', 'gdpr_at', 'user_id'
    ];

    // Relación opcional con usuario (user_id nullable)
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

}
