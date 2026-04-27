<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ApiService;

/**
 * AppServiceProvider — Point d'entrée des bindings de services.
 * Enregistre ApiService comme singleton pour éviter de créer
 * plusieurs instances HTTP dans la même requête.
 */
class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Singleton ApiService : une seule instance partagée par requête
        $this->app->singleton(ApiService::class, function ($app) {
            return new ApiService();
        });
    }

    public function boot(): void
    {
        // Partage le nom de l'application avec toutes les vues Blade
        // (peut être surchargé dynamiquement par l'admin via /settings)
        \Illuminate\Support\Facades\View::share(
            'app_name',
            config('app.name', 'LinkPulse')
        );
    }
}
