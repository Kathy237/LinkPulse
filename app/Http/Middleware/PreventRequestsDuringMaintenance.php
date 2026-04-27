<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as Middleware;

/**
 * Bloque les requêtes quand l'application est en mode maintenance.
 * URLs exclues du mode maintenance (toujours accessibles).
 */
class PreventRequestsDuringMaintenance extends Middleware
{
    protected $except = [
        // Laisser vide : aucune URL ne passe en mode maintenance
    ];
}
