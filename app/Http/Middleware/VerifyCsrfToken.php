<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * URIs that should be excluded from CSRF verification.
    
     *
     * @var array
     */

    /*
     Omitir CSRF de Stripe porque las peticiones vienen desde 
     servidores externos, se valida la firma del webhook 
     (cabecera Stripe-Signature) usando el secreto de firma 
     para asegurar que la petición
     proviene realmente de Stripe.
    */
    protected $except = [
        'stripe/webhook',
        'stripe/*',
    ];
}
