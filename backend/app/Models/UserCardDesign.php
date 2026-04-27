<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCardDesign extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'template_id', 'card_data', 'dimensions', 'front_image_url', 'back_image_url'];
    protected $casts = ['card_data' => 'array'];
    public function user() { return $this->belongsTo(User::class); }
    public function template() { return $this->belongsTo(CardTemplate::class); }
}