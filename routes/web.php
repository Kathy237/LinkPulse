
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController; 
use App\Services\MailtrapService;


use Illuminate\Http\Request;

Route::get('/test-gd', function() {
    return extension_loaded('gd') ? 'GD activé' : 'GD non activé';
});

Route::get('/reset-password/{token}', function ($token, Request $request) {
    $email = $request->query('email');
    return view('auth.reset-password', ['token' => $token, 'email' => $email]);
})->name('password.reset');

Route::get('/', function () {
   return response()->json(['status' => 'API is running']);
});


Route::get('/test-email', function () {
    try {
        Mail::raw('Test SSL réussi', function ($message) {
            $message->to('test@example.com')->subject('Problème SSL résolu');
        });
        return "✅ L'email a été envoyé avec succès.";
    } catch (\Exception $e) {
        return "❌ Erreur : " . $e->getMessage();
    }
});


Route::get('/test-db', function () {
    try {
        $pdo = DB::connection()->getPdo();
        return "✅ Connexion réussie à PostgreSQL !";
    } catch (\Exception $e) {
        return "❌ Erreur : " . $e->getMessage();
    }
});