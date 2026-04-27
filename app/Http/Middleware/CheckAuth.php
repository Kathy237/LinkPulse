<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

/**
 * CheckAuth — Protège toutes les routes nécessitant une connexion.
 * Si aucun token n'est en session, redirige vers /login.
 */
class CheckAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Vérifie la présence du token API en session
        if (!Session::has('api_token') || !Session::has('user')) {
            return redirect()->route('login')
                             ->with('error', 'Session expirée. Veuillez vous reconnecter.');
        }

        return $next($request);
    }
}
