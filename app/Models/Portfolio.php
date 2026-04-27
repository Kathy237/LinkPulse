<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Portfolio extends Model
{
    use HasFactory;

    /**
     * Les attributs assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'slug',
        'display_name',
        'bio',
        'profile_photo',
        'cv_file',
        'vcard_email',
        'vcard_phone',
        'vcard_address',
        'portfolio_external_url',
        'theme',
        'is_active',
        'hidden_at',
        'skills',
    ];

    /**
     * Les attributs à convertir.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'hidden_at' => 'datetime',
    ];

    // ==========================================
    // BOOT (slug automatique)
    // ==========================================

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($portfolio) {
            // Générer un slug unique à partir du display_name
            $baseSlug = Str::slug($portfolio->display_name);
            // Si le baseSlug est vide (ex: caractères non latins), on met une valeur par défaut
            if (empty($baseSlug)) {
                $baseSlug = 'portfolio';
            }
            $slug = $baseSlug;
            $counter = 1;
            while (static::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter++;
            }
            $portfolio->slug = $slug;
        });
    }

    // ==========================================
    // RELATIONS
    // ==========================================

    /**
     * Le portfolio appartient à un utilisateur.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Projets associés au portfolio (triés par ordre d'affichage).
     *
     * @return HasMany
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class)->orderBy('sort_order');
    }

    /**
     * Liens sociaux du portfolio.
     *
     * @return HasMany
     */
    public function socialLinks(): HasMany
    {
        return $this->hasMany(SocialLink::class)->orderBy('sort_order');
    }

    /**
     * Liens personnalisés (supplémentaires) du portfolio.
     *
     * @return HasMany
     */
    public function customLinks(): HasMany
    {
        return $this->hasMany(CustomLink::class)->orderBy('sort_order');
    }

    /**
     * Cartes NFC associées à ce portfolio (peut en avoir plusieurs historiquement).
     *
     * @return HasMany
     */
    public function nfcCards(): HasMany
    {
        return $this->hasMany(NfcCard::class);
    }

    /**
     * Carte NFC actuellement active (la première ou la plus récente).
     * Utile pour savoir si le portfolio est lié à une carte.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function activeNfcCard()
    {
        return $this->hasOne(NfcCard::class)->latest();
    }

    /**
     * Visites enregistrées pour ce portfolio (traçabilité anonyme ou connectée).
     *
     * @return HasMany
     */
    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }

    /**
     * Signalements (reports) dont ce portfolio est la cible.
     *
     * @return HasMany
     */
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, 'reported_portfolio_id');
    }

    // ==========================================
    // ACCESSORS
    // ==========================================

    /**
     * URL complète de la photo de profil (via stockage public).
     *
     * @return string|null
     */
    public function getProfilePhotoUrlAttribute(): ?string
    {
        return $this->profile_photo ? asset('storage/' . $this->profile_photo) : null;
    }

    /**
     * URL complète du fichier CV.
     *
     * @return string|null
     */
    public function getCvUrlAttribute(): ?string
    {
        return $this->cv_file ? asset('storage/' . $this->cv_file) : null;
    }

    /**
     * URL publique d'accès au portfolio (page de présentation).
     *
     * @return string
     */
    public function getPublicUrlAttribute(): string
    {
        return url('/api/public/p/' . $this->slug);
    }

    /**
     * Vérifie si le portfolio est actuellement masqué (par exemple après blocage utilisateur).
     *
     * @return bool
     */
    public function isHidden(): bool
    {
        return !$this->is_active || !is_null($this->hidden_at);
    }

    /**
     * Vérifie si le portfolio est visible publiquement.
     *
     * @return bool
     */
    public function isVisible(): bool
    {
        return $this->is_active && is_null($this->hidden_at);
    }

    // ==========================================
    // SCOPES (pour faciliter les requêtes)
    // ==========================================

    /**
     * Scope pour les portfolios visibles (actifs et non masqués).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVisible($query)
    {
        return $query->where('is_active', true)->whereNull('hidden_at');
    }

    /**
     * Scope pour les portfolios masqués (inactifs ou hidden_at renseigné).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHidden($query)
    {
        return $query->where(function ($q) {
            $q->where('is_active', false)->orWhereNotNull('hidden_at');
        });
    }

    /**
     * Scope pour filtrer par utilisateur propriétaire.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOwnedBy($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
}