<?php
// app/Policies/PortfolioPolicy.php

namespace App\Policies;

use App\Models\User;
use App\Models\Portfolio;

class PortfolioPolicy
{
    /**
     * Détermine si l'utilisateur peut voir le portfolio.
     * (pour l'édition, suppression, etc.)
     */
    public function view(User $user, Portfolio $portfolio)
    {
        // Un utilisateur ne peut voir que ses propres portfolios
        return $user->id === $portfolio->user_id;
    }

    /**
     * Détermine si l'utilisateur peut mettre à jour le portfolio.
     */
    public function update(User $user, Portfolio $portfolio)
    {
        return $user->id === $portfolio->user_id;
    }

    /**
     * Détermine si l'utilisateur peut supprimer le portfolio.
     */
    public function delete(User $user, Portfolio $portfolio)
    {
        return $user->id === $portfolio->user_id;
    }

    /**
     * Détermine si l'utilisateur peut créer un portfolio.
     */
    public function create(User $user)
    {
        // Tous les utilisateurs actifs peuvent créer
        return $user->isActive();
    }
}