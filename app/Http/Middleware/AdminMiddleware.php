<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;



//PROTEGER RUTAS DEL ADMINISTRADOR
//Solo user que is_admin sea true puede hacer.
class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->is_admin) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'No tienes permiso para acceder a esta página.'], 403);
            }

            return redirect('/')->with('error', 'No tienes permiso para acceder a esta página.');
        }

        return $next($request);
    }
}
