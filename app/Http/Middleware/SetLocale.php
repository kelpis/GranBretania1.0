<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App as LaravelApp;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;



class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // Si hay idioma en sesión, úsalo; si no, el de config/app.php
        app()->setLocale(session('locale', config('app.locale')));

        return $next($request);
    }
}
