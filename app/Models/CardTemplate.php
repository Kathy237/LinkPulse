<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardTemplate extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image_url', 'template_data', 'is_active', 'sort_order'];
    protected $casts = ['template_data' => 'array', 'is_active' => 'boolean'];
}