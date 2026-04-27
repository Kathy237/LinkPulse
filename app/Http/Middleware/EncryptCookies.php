<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

/**
 * EncryptCookies — Chiffre tous les cookies sauf les exceptions définies.
 * Le cookie visitor_id (identifiant anonyme visiteur) est exclu
 * pour pouvoir être lu facilement côté JavaScript si nécessaire.
 */
class EncryptCookies extends Middleware
{
    /**
     * Noms des cookies à NE PAS chiffrer.
     */
    protected $except = [
        // 'visitor_id', // Décommenter si besoin d'accès JS au cookie visiteur
    ];
}
