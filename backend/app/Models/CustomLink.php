<?php
// app/Models/CustomLink.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'portfolio_id',
        'label',
        'url',
        'icon',
        'sort_order',
    ];

    /**
     * Relation : le lien personnalisé appartient à un portfolio.
     */
    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }
}