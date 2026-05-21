<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StaffReadOnly
{
  public function handle(Request $request, Closure $next): Response
  {
    // Si el usuario es 'staff' y la petición NO es GET, bloqueamos
    if ($request->user() && $request->user()->role === 'staff' && !$request->isMethod('GET')) {
      return back()->with('error', 'Acceso restringido: El personal solo tiene permisos de visualización.');
    }

    return $next($request);
  }
}