<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

/**
 * VerifyCsrfToken — Exclut certaines routes de la vérification CSRF.
 *
 * Les routes publiques de type webhook NFC sont exclues car elles
 * peuvent être appelées par des équipements externes sans token CSRF.
 */
class VerifyCsrfToken extends Middleware
{
    /**
     * Routes exclues de la protection CSRF.
     * Toutes les autres routes POST sont protégées par @csrf.
     */
    protected $except = [
        // Pas d'exclusion nécessaire pour LinkPulse frontend
        // Toutes les routes POST utilisent @csrf dans les formulaires Blade
    
        // 'login',
        //'register',
    ];
}
