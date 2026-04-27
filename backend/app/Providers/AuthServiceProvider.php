<?php

namespace App\Providers;

use App\Models\Portfolio;
use App\Policies\PortfolioPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Les correspondances modèle → politique.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Portfolio::class => PortfolioPolicy::class,
    ];

    /**
     * Enregistre les services d'authentification / autorisation.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Donne tous les droits aux administrateurs
        Gate::before(function ($user, $ability) {
            if ($user->role === 'admin') {
                return true;
            }
        });
    }
}