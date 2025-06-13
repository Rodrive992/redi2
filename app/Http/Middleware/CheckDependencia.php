<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDependencia
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   public function handle(Request $request, Closure $next)
{
    $user = $request->user();
    
    if (!$user) {
        return redirect()->route('login');
    }

    // Si es DGP y está intentando acceder a redi-externo
    if ($user->dependencia === 'dgp' && $request->route()->named('redi.externo')) {
        return redirect()->route('redidgp');
    }

    // Si no es DGP y está intentando acceder a redidgp
    if ($user->dependencia !== 'dgp' && $request->route()->named('redidgp')) {
        return redirect()->route('redi.externo');
    }

    return $next($request);
}
}
