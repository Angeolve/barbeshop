<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Si no está logueado, mandarlo al login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Verificar si el rol del usuario está dentro de los roles permitidos en la ruta
        if (in_array(Auth::user()->role, $roles)) {
            return $next($request);
        }

        // 3. Si está logueado pero no tiene el rol correcto, abortar con un error 403 (No autorizado)
        abort(403, 'No tienes autorización para acceder a esta sección.');
    }
}