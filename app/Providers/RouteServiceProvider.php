<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

/**
 * RouteServiceProvider — Charge les fichiers de routes.
 * Définit aussi les rate-limiters pour l'API.
 */
class RouteServiceProvider extends ServiceProvider
{
    /**
     * Redirection après connexion réussie (utilisé par Breeze).
     * Ici on gère la redirection manuellement dans AuthController,
     * mais cette constante est requise par certains packages.
     */
    public const HOME = '/espace/dashboard';

    public function boot(): void
    {
        // Limite de taux pour les routes API
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            // Routes API (non utilisées ici, tout passe par le backend)
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // Routes Web (toutes nos routes frontend)
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
