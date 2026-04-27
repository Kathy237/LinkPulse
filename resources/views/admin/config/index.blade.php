@extends('layouts.app')

@section('title', 'Configuration')
@section('page-title', 'Configuration')

@section('content')

<div class="grid-2" style="gap:24px;">

    {{-- ══ PARAMÈTRES GÉNÉRAUX ══ --}}
    <div class="card">
        <div class="card-header">
            <i class="ri-settings-3-line" style="color:var(--color-accent);margin-right:8px;"></i>
            Paramètres généraux
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.config.update') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="app_name">Nom de l'application</label>
                    <input type="text" name="app_name" id="app_name"
                           class="form-control"
                           value="{{ $settings['app_name'] ?? config('app.name') }}"
                           placeholder="LinkPulse"
                           maxlength="100">
                    <div class="text-muted text-xs" style="margin-top:4px;">
                        Ce nom s'affiche partout dans l'interface pour tous les utilisateurs.
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="default_language">Langue de l'interface</label>
                    <select name="default_language" id="default_language" class="form-control">
                        <option value="fr" {{ ($settings['default_language'] ?? 'fr') === 'fr' ? 'selected' : '' }}>
                            🇫🇷 Français
                        </option>
                        <option value="en" {{ ($settings['default_language'] ?? '') === 'en' ? 'selected' : '' }}>
                            🇬🇧 English
                        </option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="ri-save-line"></i> Enregistrer
                </button>
            </form>
        </div>
    </div>

    {{-- ══ MON MOT DE PASSE ══ --}}
    <div class="card">
        <div class="card-header">
            <i class="ri-lock-password-line" style="color:var(--color-accent);margin-right:8px;"></i>
            Modifier mon mot de passe
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.config.password') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="current_password">Mot de passe actuel</label>
                    <div style="position:relative;">
                        <input type="password" name="current_password" id="current_password"
                               class="form-control {{ $errors->has('current_password') ? 'is-invalid' : '' }}"
                               placeholder="••••••••" required>
                        <i class="ri-eye-off-line" id="toggle-cur"
                           style="position:absolute;right:12px;top:50%;transform:translateY(-50%);cursor:pointer;color:var(--color-muted);font-size:16px;"></i>
                    </div>
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="new_password">Nouveau mot de passe</label>
                    <input type="password" name="password" id="new_password"
                           class="form-control"
                           placeholder="Minimum 8 caractères" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Confirmation</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="form-control"
                           placeholder="Répétez le nouveau mot de passe" required>
                </div>

                <button type="submit" class="btn btn-warning">
                    <i class="ri-lock-unlock-line"></i> Modifier le mot de passe
                </button>
            </form>
        </div>
    </div>

    {{-- ══ AJOUTER UN ADMINISTRATEUR ══ --}}
    <div class="card">
        <div class="card-header">
            <i class="ri-user-star-line" style="color:var(--color-accent);margin-right:8px;"></i>
            Ajouter un administrateur
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.config.add_admin') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="admin_name">Nom complet</label>
                    <input type="text" name="name" id="admin_name"
                           class="form-control"
                           placeholder="Nom Prénom" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="admin_email">Adresse email</label>
                    <input type="email" name="email" id="admin_email"
                           class="form-control"
                           placeholder="admin@exemple.com" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="admin_password">Mot de passe temporaire</label>
                    <input type="password" name="password" id="admin_password"
                           class="form-control"
                           placeholder="Minimum 8 caractères" required>
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="ri-user-add-line"></i> Créer l'administrateur
                </button>
            </form>
        </div>
    </div>

    {{-- ══ LISTE DES ADMINS ══ --}}
    <div class="card">
        <div class="card-header">
            <i class="ri-shield-user-line" style="color:var(--color-accent);margin-right:8px;"></i>
            Administrateurs existants
        </div>
        <div class="card-body">
            @if(!empty($admins))
            <div style="display:flex;flex-direction:column;gap:10px;">
                @foreach($admins as $admin)
                <div style="display:flex;align-items:center;gap:12px;padding:10px;background:var(--color-surface);border-radius:8px;">
                    <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#7c3aed,var(--color-accent));display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:14px;flex-shrink:0;">
                        {{ strtoupper(substr($admin['name'] ?? 'A', 0, 1)) }}
                    </div>
                    <div style="min-width:0;">
                        <div style="font-size:14px;font-weight:500;color:var(--color-white);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            {{ $admin['name'] }}
                            @if($admin['email'] === session('user.email'))
                                <span style="font-size:11px;color:var(--color-accent);">(vous)</span>
                            @endif
                        </div>
                        <div class="text-xs text-muted" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            {{ $admin['email'] }}
                        </div>
                    </div>
                    <span class="badge badge-info" style="margin-left:auto;flex-shrink:0;">Admin</span>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-muted text-sm">Aucun autre administrateur.</p>
            @endif
        </div>
    </div>

</div>

@push('scripts')
<script>
// Toggle visibilité mot de passe actuel
document.getElementById('toggle-cur').addEventListener('click', function() {
    const i = document.getElementById('current_password');
    const h = i.type === 'password';
    i.type = h ? 'text' : 'password';
    this.className = h ? 'ri-eye-line' : 'ri-eye-off-line';
    this.style.cssText = 'position:absolute;right:12px;top:50%;transform:translateY(-50%);cursor:pointer;color:var(--color-muted);font-size:16px;';
});
</script>
@endpush

@endsection
