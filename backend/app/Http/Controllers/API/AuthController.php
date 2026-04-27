<?php
// app/Http/Controllers/API/AuthController.php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AuthController extends Controller
{
    /**
     * Inscription d'un nouvel utilisateur.
     * Le statut par défaut est 'pending' en attendant validation admin.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'nullable|string|max:100',
            'last_name'  => 'nullable|string|max:100',
            'name'       => 'required|string|max:100',
            'email'      => 'required|email|unique:users',
            'password'   => 'required|string|min:8|confirmed',
            'phone'      => 'nullable|string|max:50',
            'location'   => 'nullable|string',
        ]);

        $user = User::create([
            'first_name' => $validated['first_name'] ?? null,
            'last_name'  => $validated['last_name'] ?? null,
            'name'       => $validated['name'],
            'email'      => $validated['email'],
            'password'   => Hash::make($validated['password']),
            'phone'      => $validated['phone'] ?? null,
            'location'   => $validated['location'] ?? null,
            'role'       => 'user',
            'status'     => 'pending', // Statut par défaut 'en attente' selon la consigne
        ]);

        // Notification par mail automatique à kathywassu@gmail.com
        Mail::raw("Une nouvelle inscription a été effectuée par {$user->name} ({$user->email}). Le compte est en attente de validation.", function ($message) {
            $message->to('kathywassu@gmail.com')
                    ->subject('Nouvelle inscription sur LinkPulse (En attente)');
        });

        return response()->json([
            'message' => 'Inscription réussie. Votre compte est en attente de validation par un administrateur.',
            'user' => new UserResource($user),
        ], 201);
    }

    /**
     * Connexion et délivrance d'un token Sanctum.
     * Seuls les utilisateurs avec status 'active' peuvent se connecter.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Les identifiants sont incorrects.'],
            ]);
        }

        if ($user->status !== 'active') {
            throw ValidationException::withMessages([
                'email' => ['Votre compte n\'est pas encore activé. Veuillez attendre la validation de l\'administrateur.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => new UserResource($user),
        ]);
    }

    /**
     * Déconnexion : révoquer le token actuel.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Déconnecté avec succès.']);
    }

    /**
     * Récupérer l'utilisateur connecté.
     */
    public function me(Request $request)
    {
        return new UserResource($request->user());
    }


    public function updateProfile(Request $request)
    {
    $user = $request->user();
    $validated = $request->validate([
        'name' => 'sometimes|string|max:100',
        'first_name' => 'nullable|string|max:100',
        'last_name' => 'nullable|string|max:100',
        'phone' => 'nullable|string|max:50',
        'location' => 'nullable|string',
        'locale' => 'nullable|string|in:fr,en',
        'password' => 'nullable|string|min:8|confirmed',
    ]);
    if (isset($validated['password'])) {
        $validated['password'] = Hash::make($validated['password']);
    }
    $user->update($validated);
    return new UserResource($user);
    }
    

    /**
     * Demande de réinitialisation de mot de passe (envoi d'un email avec lien).
     */
    
    public function forgotPassword(Request $request)
    {
    $request->validate(['email' => 'required|email']);

    $user = User::where('email', $request->email)->first();
    if (!$user) {
        return response()->json(['message' => 'Aucun utilisateur avec cet email.'], 404);
    }

    // Générer un token aléatoire
    $token = Str::random(60);

    // Stocker dans la table password_reset_tokens
    DB::table('password_reset_tokens')->updateOrInsert(
        ['email' => $user->email],
        ['token' => $token, 'created_at' => Carbon::now()]
    );

    // Construire l'URL vers votre formulaire (web)
    $url = url("/reset-password/{$token}?email=" . urlencode($user->email));

    // Envoyer l'email
    Mail::send('emails.reset-password', ['user' => $user, 'url' => $url], function ($message) use ($user) {
        $message->to($user->email)->subject('Réinitialisation de votre mot de passe');
    });

    return response()->json(['message' => 'Email envoyé avec succès.']);
    }

    /**
     * Réinitialisation du mot de passe avec token.
     */
    
    public function resetPassword(Request $request)
{
    $request->validate([
        'token' => 'required|string',
        'email' => 'required|email',
        'password' => 'required|string|min:8|confirmed',
    ]);

    // Nettoyer le token (supprimer espaces éventuels)
    $token = trim($request->token);

    // Récupérer l'enregistrement
    $reset = DB::table('password_reset_tokens')
        ->where('email', $request->email)
        ->first();

    if (!$reset || !hash_equals($reset->token, $token)) {
        return response()->json(['message' => 'Token invalide.'], 400);
    }

    // Vérifier l'expiration en comparant les timestamps UTC
    $createdAt = Carbon::parse($reset->created_at)->setTimezone('UTC');
    $now = Carbon::now('UTC');
    if ($now->diffInMinutes($createdAt) > 60) {
        return response()->json(['message' => 'Token expiré.'], 400);
    }

    // Mettre à jour le mot de passe
    $user = User::where('email', $request->email)->first();
    $user->password = Hash::make($request->password);
    $user->save();

    // Supprimer le token utilisé
    DB::table('password_reset_tokens')->where('email', $request->email)->delete();

    return response()->json(['message' => 'Mot de passe réinitialisé avec succès.']);
}


    public function showResetForm($token, $email)
    {
    // Retourner une vue simple (ou un JSON avec token/email pour le frontend)
    return view('auth.reset-password', [
        'token' => $token,
        'email' => $email,
    ]);
    }
}