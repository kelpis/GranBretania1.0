<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

// Middleware para proteger rutas de administrador.
// Solo permite acceso a usuarios autenticados que tengan el atributo 'is_admin' en true.


class AdminMiddleware
{
    // Método handle:verifica autenticación y rol de admin antes de continuar.
    public function handle(Request $request, Closure $next)
    {
        // Verificar si el usuario está autenticado y es administrador.
        if (!Auth::check() || !Auth::user()->is_admin) {
            // Si la solicitud espera JSON (API), devolver respuesta JSON con error 403.
            if ($request->expectsJson()) {
                return response()->json(['message' => 'No tienes permiso para acceder a esta página.'], 403);
            }

            // Para solicitudes normales, redirigir al home con mensaje de error.
            return redirect('/')->with('error', 'No tienes permiso para acceder a esta página.');
        }

        // Si pasa la verificación, continuar con la solicitud.
        return $next($request);
    }
}
