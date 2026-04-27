<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Auth\Notifications\ResetPassword;
use App\Models\UserCardDesign;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
       'first_name', 'last_name', 'name', 'email', 'password', 'phone', 
        'location', 'locale', 'role', 'status', 'blocked_at', 'restorable_until'
    ];

    /**
     * Les attributs qui doivent être masqués pour les tableaux JSON.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Les attributs qui doivent être convertis vers les types natifs.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'blocked_at' => 'datetime',
        'restorable_until' => 'datetime',
    ];

    // ==========================================
    // RELATIONS
    // ==========================================

    /**
     * Relation : un utilisateur a plusieurs portfolios.
     */
    public function portfolios(): HasMany
    {
        return $this->hasMany(Portfolio::class);
    }

    //Un utilisateur a un quota (forfait) défini par l'administrateur
    public function quota(): HasOne
    {
        return $this->hasOne(UserQuota::class);
    }


    /**
     * Relation : les lectures NFC effectuées par l'utilisateur.
     */
    public function nfcReads(): HasMany
    {
        return $this->hasMany(NfcRead::class);
    }

    /**
     * Relation : les cartes NFC créées par l'utilisateur.
     */
    public function nfcCardsCreated(): HasMany
    {
        return $this->hasMany(NfcCard::class, 'created_by');
    }

    // Relation: Les visites effectués par le visiteur 
    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }
  
    /**
      * Relation : les designs de carte créés par l'utilisateur.
    */
    public function userCardDesigns(): HasMany
    {
        return $this->hasMany(UserCardDesign::class);
    }
    /**
     * Portfolios consultés par l'utilisateur (historique de visites quand il est connecté).
     * Table pivot : user_views
     *
     * @return BelongsToMany
     */
    public function viewedPortfolios(): BelongsToMany
    {
        return $this->belongsToMany(Portfolio::class, 'user_views')
                    ->withPivot('viewed_at')
                    ->orderByPivot('viewed_at', 'desc');
    }

    /**
     * Vérifier si l'utilisateur est administrateur.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Vérifier si l'utilisateur est actif (status = active).
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Vérifie si l'utilisateur est bloqué (status = 'rejected' ou blocked_at non null)
     * et si la date de restauration est dépassée ou non.
     * On considère un utilisateur bloqué si son status est 'rejected'
     * OU (blocked_at n'est pas null et restorable_until est passé).
     *
     * @return bool
     */
    public function isBlocked(): bool
    {
        if ($this->status === 'rejected') {
            return true;
        }

        if ($this->blocked_at && $this->restorable_until && now()->gt($this->restorable_until)) {
            return true;
        }

        return false;
    }

    /**
     * Vérifie si l'utilisateur peut encore être restauré (période de 30 jours non écoulée).
     *
     * @return bool
     */
    public function isRestorable(): bool
    {
        return $this->blocked_at && $this->restorable_until && now()->lt($this->restorable_until);
    }

    // ==========================================
    // SCOPE LOCALES (pour faciliter les requêtes)
    // ==========================================

    /**
     * Scope pour récupérer uniquement les utilisateurs actifs.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope pour récupérer les utilisateurs en attente.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope pour récupérer les utilisateurs rejetés/bloqués.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
    
    public function sendPasswordResetNotification($token)
    {
    $url = url('/reset-password/' . $token . '/' . urlencode($this->email));
    $this->notify(new \Illuminate\Auth\Notifications\ResetPassword($url));
    }
}