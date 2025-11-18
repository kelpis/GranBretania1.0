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
        // Coge el {locale} de la URL (es / en) si existe
        $locale = $request->route('locale');

        if (! in_array($locale, ['es', 'en'])) {
            $locale = config('app.locale'); // normalmente 'es'
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
