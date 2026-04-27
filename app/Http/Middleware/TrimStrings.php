<?php
// app/Http/Middleware/TrimStrings.php
namespace App\Http\Middleware;
use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;
class TrimStrings extends Middleware {
    // Champs exclus du trim automatique (mots de passe)
    protected $except = ['current_password','password','password_confirmation'];
}
