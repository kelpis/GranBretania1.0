<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    
    //Campos asignables al guardar
    protected $fillable = ['name','email','subject','message', 'gdpr_given', 'gdpr_at'];
}
