<?php
namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * URIs that should be excluded from CSRF verification.
     * Stripe webhooks must be exempted because they are POSTs from Stripe.
     *
     * @var array
     */
    protected $except = [
        '/stripe/webhook',
             
    ];
}
