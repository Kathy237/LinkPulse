<?php
// app/Models/NfcRead.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NfcRead extends Model
{
    use HasFactory;

    // Désactive les timestamps automatiques car nous utilisons read_at manuellement
    public $timestamps = false;

    protected $fillable = [
        'card_id',
        'user_id',
        'visitor_device_id',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    /**
     * Relation : la lecture appartient à une carte.
     */
    public function card(): BelongsTo
    {
        return $this->belongsTo(NfcCard::class, 'card_id');
    }

    /**
     * Relation : la lecture appartient à un utilisateur.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}