<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

/**
 * Authenticate — Middleware d'authentification Laravel standard.
 * Redirige vers /login si l'utilisateur n'est pas authentifié.
 *
 * Note : LinkPulse utilise CheckAuth (session API token) plutôt que
 * le système d'auth Laravel natif, mais ce middleware est requis
 * par le framework.
 */
class Authenticate extends Middleware
{
    /**
     * Retourne le chemin de redirection pour les utilisateurs non authentifiés.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }
}
