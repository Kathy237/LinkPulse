<?php
// app/Models/NfcCard.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NfcCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'uid',
        'portfolio_id',
        'created_by',
    ];

    /**
     * Relation : la carte appartient à un portfolio.
     */
    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }

    /**
     * Relation : l'utilisateur qui a créé l'association.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relation : les lectures de cette carte.
     */
    public function reads()
    {
        return $this->hasMany(NfcRead::class, 'card_id');
    }
}