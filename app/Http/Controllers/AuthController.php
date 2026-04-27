<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

/**
 * AuthController — Gère toute l'authentification frontend.
 * Les données sont envoyées au backend, qui retourne un token JWT (Sanctum).
 * Ce token est stocké en session pour les requêtes suivantes.
 */
class AuthController extends Controller
{
    protected ApiService $api;

    public function __construct(ApiService $api)
    {
        $this->api = $api;
    }

    // ─────────────────────────────────────────────────────────────
    // PAGE D'ACCUEIL
    // ─────────────────────────────────────────────────────────────

    /** Page d'accueil publique (landing page animée) */
    public function home()
    {
        // Si déjà connecté, rediriger vers son espace
        if (Session::has('api_token')) {
            $user = Session::get('user');
            return ($user['role'] ?? '') === 'admin'
                ? redirect()->route('admin.dashboard')
                : redirect()->route('user.dashboard');
        }
        return view('welcome');
    }

    // ─────────────────────────────────────────────────────────────
    // CONNEXION
    // ─────────────────────────────────────────────────────────────

    /** Affiche le formulaire de connexion */
    public function showLogin()
    {
        if (Session::has('api_token')) {
            return redirect()->route('home');
        }
        return view('auth.login');
    }

    /** Traite la soumission du formulaire de connexion */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required'    => 'L\'adresse email est obligatoire.',
            'email.email'       => 'L\'adresse email n\'est pas valide.',
            'password.required' => 'Le mot de passe est obligatoire.',
        ]);

        // Envoie les identifiants au backend
        $response = $this->api->post('/login', [
            'email'    => $request->email,
            'password' => $request->password,
        ]);

        if ($response->successful()) {
            $data = $response->json();

            // Stocke le token et les infos utilisateur en session
            Session::put('api_token', $data['access_token']);
            Session::put('user', $data['user']);

            // Redirige selon le rôle
            if (($data['user']['role'] ?? '') === 'admin') {
                return redirect()->route('admin.dashboard')
                                 ->with('success', 'Bienvenue, administrateur !');
            }

            return redirect()->route('user.dashboard')
                             ->with('success', 'Connexion réussie. Bienvenue !');
        }

        // Erreur côté backend (mauvais identifiants, compte non approuvé, etc.)
        $message = $response->json('message') ?? 'Identifiants incorrects.';
        return back()->withInput($request->only('email'))
                     ->with('error', $message);
    }

    // ─────────────────────────────────────────────────────────────
    // INSCRIPTION
    // ─────────────────────────────────────────────────────────────

    /** Affiche le formulaire d'inscription */
    public function showRegister()
    {
        return view('auth.register');
    }

    /** Traite l'inscription d'un nouvel utilisateur */
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email|max:255',
            'phone'      => 'required|string|max:30',
            'location'   => 'required|string|max:255',
            'password'   => 'required|min:8|confirmed',
        ], [
            'first_name.required' => 'Le prénom est obligatoire.',
            'last_name.required'  => 'Le nom est obligatoire.',
            'email.required'      => 'L\'email est obligatoire.',
            'phone.required'      => 'Le numéro de téléphone est obligatoire.',
            'location.required'   => 'La localisation est obligatoire.',
            'password.min'        => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed'  => 'Les mots de passe ne correspondent pas.',
        ]);

        // Envoi au backend
        $response = $this->api->post('/register', [
            'name'       => $request->first_name . ' ' . $request->last_name,
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'location'   => $request->location,
            'password'   => $request->password,
            'password_confirmation' => $request->password_confirmation,
        ]);

        if ($response->successful()) {
            return redirect()->route('login')
                             ->with('success', 'Inscription réussie ! Vous recevrez un email dès validation de votre compte par l\'administrateur.');
        }

        $errors = $response->json('errors') ?? [];
        $message = $response->json('message') ?? 'Une erreur est survenue lors de l\'inscription.';

        return back()->withInput($request->except('password', 'password_confirmation'))
                     ->withErrors($errors)
                     ->with('error', $message);
    }

    // ─────────────────────────────────────────────────────────────
    // DÉCONNEXION
    // ─────────────────────────────────────────────────────────────

    /** Déconnecte l'utilisateur — invalide le token côté backend */
    public function logout(Request $request)
    {
        // Informer le backend d'invalider le token
        $this->api->post('/logout');

        // Vider la session frontend
        Session::flush();

        return redirect()->route('login')
                         ->with('success', 'Vous avez été déconnecté avec succès.');
    }

    // ─────────────────────────────────────────────────────────────
    // MOT DE PASSE OUBLIÉ
    // ─────────────────────────────────────────────────────────────

    /** Affiche le formulaire "mot de passe oublié" */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /** Envoie le code de réinitialisation par email */
    public function sendResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email'    => 'L\'adresse email n\'est pas valide.',
        ]);

        $response = $this->api->post('/forgot-password', [
            'email' => $request->email,
        ]);

        if ($response->successful()) {
            // Stocker l'email en session pour l'étape suivante
            Session::put('reset_email', $request->email);
            return redirect()->route('password.reset.form')
                             ->with('success', 'Un code de vérification a été envoyé à votre adresse email.');
        }

        return back()->with('error', $response->json('message') ?? 'Email introuvable.');
    }

    /** Affiche le formulaire de saisie du code + nouveau mot de passe */
    public function showResetPassword()
    {
        return view('auth.reset-password');
    }

    /** Traite la réinitialisation du mot de passe */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required|string',
            'password' => 'required|min:8|confirmed',
        ], [
            'token.required'     => 'Le code de vérification est obligatoire.',
            'password.min'       => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ]);

        $response = $this->api->post('/reset-password', [
            'email'                 => Session::get('reset_email'),
            'token'                 => $request->token,
            'password'              => $request->password,
            'password_confirmation' => $request->password_confirmation,
        ]);

        if ($response->successful()) {
            Session::forget('reset_email');
            return redirect()->route('login')
                             ->with('success', 'Mot de passe réinitialisé avec succès. Vous pouvez vous connecter.');
        }

        return back()->with('error', $response->json('message') ?? 'Code invalide ou expiré.');
    }
}
