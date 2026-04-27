<?php
// app/Models/Visit.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Visit extends Model
{
    use HasFactory;

    // Désactive les timestamps (nous utilisons visited_at)
    public $timestamps = false;

    protected $fillable = [
        'portfolio_id',
        'source',
        'ip_address',
        'user_agent',
        'visited_at',
        'device_id', 
        'visitor_name', 
        'visitor_email', 
        'visitor_phone',
        'visitor_address',
        'visitor_social_links', 
        'user_id',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
        'visitor_social_links' => 'array',
    ];

    /**
     * Relation : la visite appartient à un portfolio.
     */
    public function portfolio(): BelongsTo
    {
        return $this->belongsTo(Portfolio::class);
    }

    /**
     * Relation : la visite peut être associée à un utilisateur connecté.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}