<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

/**
 * CheckAdmin — Protège toutes les routes /admin.
 * Vérifie que l'utilisateur connecté possède le rôle 'admin'.
 * Si ce n'est pas le cas, redirige vers le login.
 */
class CheckAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = Session::get('user');

        // Pas connecté → login
        if (!$user) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter.');
        }

        // Connecté mais pas admin → accueil utilisateur
        if (($user['role'] ?? '') !== 'admin') {
            return redirect()->route('user.dashboard')
                             ->with('error', 'Accès réservé aux administrateurs.');
        }

        return $next($request);
    }
}
