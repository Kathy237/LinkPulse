<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

/**
 * RedirectIfAuthenticated — Redirige l'utilisateur déjà connecté
 * vers son espace (admin ou utilisateur) s'il tente d'accéder
 * aux pages login / register.
 */
class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        // Si un token est en session, l'utilisateur est connecté
        if (Session::has('api_token') && Session::has('user')) {
            $user = Session::get('user');

            // Redirige selon le rôle
            if (($user['role'] ?? '') === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('user.dashboard');
        }

        return $next($request);
    }
}
