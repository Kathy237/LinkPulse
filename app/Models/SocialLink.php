<?php
// app/Models/SocialLink.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'portfolio_id',
        'platform',
        'url',
        'sort_order',
    ];

    /**
     * Relation : le lien social appartient à un portfolio.
     */
    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }
}