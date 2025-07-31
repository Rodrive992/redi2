<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class UsuarioExterno
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if (!$user || $user->dependencia == 'dgp') {
            abort(403, 'Acceso restringido a usuarios Redi-Externo');
        }

        return $next($request);
    }
}