<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, \Closure $next)
    {
        // Si el usuario está logueado Y tiene needs_password_change en true
        if (auth()->check() && auth()->user()->needs_password_change) {

            // Evitamos que se quede en un bucle infinito excluyendo la ruta de cambio de clave y la de salir
            if (!$request->routeIs('password.force-change') && !$request->routeIs('logout')) {
                return redirect()->route('password.force-change');
            }
        }

        return $next($request);
    }
}
