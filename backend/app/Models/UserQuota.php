<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserQuota extends Model
{
    use HasFactory;

    /**
     * La table associée.
     *
     * @var string
     */
    protected $table = 'user_quotas';

    /**
     * Attributs assignables.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'max_portfolios',
        'max_links_per_portfolio',
        'max_cards',
        'valid_until',
    ];

    /**
     * Casts.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'max_portfolios'            => 'integer',
        'max_links_per_portfolio'   => 'integer',
        'max_cards'                 => 'integer',
        'valid_until'               => 'datetime',
    ];

    /**
     * Relation inverse : le quota appartient à un utilisateur.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Vérifier si le quota est encore valide.
     */
    public function isValid(): bool
    {
        return is_null($this->valid_until) || $this->valid_until->isFuture();
    }

    /**
     * Récupérer la limite de portfolios (si null = illimité).
     */
    public function getMaxPortfolios(): ?int
    {
        return $this->max_portfolios;
    }

    /**
     * Récupérer la limite de liens par portfolio.
     */
    public function getMaxLinksPerPortfolio(): ?int
    {
        return $this->max_links_per_portfolio;
    }

    /**
     * Récupérer la limite de designs de carte.
     */
    public function getMaxCards(): ?int
    {
        return $this->max_cards;
    }
}