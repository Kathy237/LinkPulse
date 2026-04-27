<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    use HasFactory;

    /**
     * La table associée au modèle.
     *
     * @var string
     */
    protected $table = 'reports';
    public $timestamps = false;

    /**
     * Les attributs assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reporter_name',
        'reporter_email',
        'reported_portfolio_id',
        'reported_user_id',
        'message',
        'status',
        'admin_notified',
        'admin_notes',
        'resolved_at',
    ];

    /**
     * Les attributs à convertir.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'admin_notified' => 'boolean',
        'created_at'     => 'datetime',
        'resolved_at'    => 'datetime',
    ];

    /**
     * Relation : le signalement peut cibler un portfolio.
     */
    public function reportedPortfolio(): BelongsTo
    {
        return $this->belongsTo(Portfolio::class, 'reported_portfolio_id');
    }

    /**
     * Relation : le signalement peut cibler un utilisateur.
     */
    public function reportedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_user_id');
    }

    /**
     * Scope pour les signalements en attente.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope pour les signalements non encore notifiés à l'admin.
     */
    public function scopeNotNotified($query)
    {
        return $query->where('admin_notified', false);
    }
}