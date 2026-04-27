@extends('layouts.app')

@section('title', 'Configuration')
@section('page-title', 'Configuration')

@section('content')

<div style="max-width:700px;">

    {{-- ══ INFORMATIONS PERSONNELLES ══ --}}
    <div class="card" style="margin-bottom:24px;">
        <div class="card-header">
            <i class="ri-user-settings-line" style="color:var(--color-accent);margin-right:8px;"></i>
            Mes informations personnelles
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('user.config.profile') }}">
                @csrf

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label class="form-label" for="name">Nom complet</label>
                        <input type="text" name="name" id="name"
                               class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                               value="{{ old('name', $user['name'] ?? '') }}"
                               required maxlength="100">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">Adresse email</label>
                        <input type="email" name="email" id="email"
                               class="form-control"
                               value="{{ old('email', $user['email'] ?? '') }}"
                               required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="phone">Téléphone</label>
                        <input type="text" name="phone" id="phone"
                               class="form-control"
                               value="{{ old('phone', $user['phone'] ?? '') }}"
                               placeholder="+237 6XX XXX XXX">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="location">Localisation</label>
                        <input type="text" name="location" id="location"
                               class="form-control"
                               value="{{ old('location', $user['location'] ?? '') }}"
                               placeholder="Douala, Cameroun">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="language">Langue de l'interface</label>
                    <select name="language" id="language" class="form-control" style="padding-left:12px;">
                        <option value="fr" {{ ($user['language'] ?? 'fr') === 'fr' ? 'selected' : '' }}>🇫🇷 Français</option>
                        <option value="en" {{ ($user['language'] ?? '') === 'en' ? 'selected' : '' }}>🇬🇧 English</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="ri-save-line"></i> Enregistrer les modifications
                </button>
            </form>
        </div>
    </div>

    {{-- ══ MOT DE PASSE ══ --}}
    <div class="card" style="margin-bottom:24px;">
        <div class="card-header">
            <i class="ri-lock-password-line" style="color:var(--color-accent);margin-right:8px;"></i>
            Modifier le mot de passe
        </div>
        <div class="card-body">
            <p class="text-muted text-sm" style="margin-bottom:20px;">
                Pour modifier votre mot de passe, utilisez le lien "Mot de passe oublié" depuis la page de connexion, ou contactez l'administrateur.
            </p>
            <a href="{{ route('password.request') }}" class="btn btn-secondary">
                <i class="ri-lock-unlock-line"></i> Réinitialiser via email
            </a>
        </div>
    </div>

    {{-- ══ INFORMATIONS DU COMPTE ══ --}}
    <div class="card">
        <div class="card-header">
            <i class="ri-information-line" style="color:var(--color-accent);margin-right:8px;"></i>
            Informations du compte
        </div>
        <div class="card-body">
            <div style="display:flex;flex-direction:column;gap:12px;">
                <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid var(--color-border);">
                    <span class="text-muted text-sm">Statut du compte</span>
                    <span class="badge badge-success">Approuvé</span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid var(--color-border);">
                    <span class="text-muted text-sm">Rôle</span>
                    <span class="badge badge-info">{{ ucfirst($user['role'] ?? 'utilisateur') }}</span>
                </div>
                @if(!empty($user['created_at']))
                <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;">
                    <span class="text-muted text-sm">Membre depuis</span>
                    <span style="font-size:14px;">{{ \Carbon\Carbon::parse($user['created_at'])->format('d/m/Y') }}</span>
                </div>
                @endif
            </div>

            <hr class="divider">

            {{-- Déconnexion --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm">
                    <i class="ri-logout-box-r-line"></i> Se déconnecter
                </button>
            </form>
        </div>
    </div>

</div>

@endsection
