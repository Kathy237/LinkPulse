<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription — LinkPulse</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:opsz,wght@9..40,400;9..40,500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        :root {
            --bg:#060a12; --surface:#0b1120; --card:#0e1420;
            --border:#1e2a40; --accent:#00d4ff; --accent2:#7c3aed;
            --text:#e2e8f0; --muted:#64748b;
            --danger:#ef4444; --success:#10b981;
            --font-d:'Syne',sans-serif; --font-b:'DM Sans',sans-serif;
        }
        *, *::before, *::after { box-sizing:border-box; margin:0; padding:0; }
        body {
            font-family:var(--font-b); background:var(--bg); color:var(--text);
            min-height:100vh; display:flex; align-items:center; justify-content:center;
            padding: 40px 20px;
        }
        body::before {
            content:''; position:fixed; inset:0; z-index:0;
            background:
                radial-gradient(ellipse 60% 50% at 20% 30%,rgba(0,212,255,0.06),transparent),
                radial-gradient(ellipse 60% 50% at 80% 70%,rgba(124,58,237,0.06),transparent);
        }
        .auth-container { position:relative; z-index:1; width:100%; max-width:540px; }
        .auth-logo { text-align:center; margin-bottom:32px; }
        .logo-link { text-decoration:none; display:inline-flex; flex-direction:column; align-items:center; gap:10px; }
        .logo-icon {
            width:52px; height:52px;
            background:linear-gradient(135deg,var(--accent),var(--accent2));
            border-radius:14px; display:flex; align-items:center; justify-content:center;
            font-size:24px; color:#fff; box-shadow:0 8px 32px rgba(0,212,255,0.25);
        }
        .logo-text { font-family:var(--font-d); font-size:26px; font-weight:800; color:#fff; }

        .auth-card {
            background:var(--card); border:1px solid var(--border);
            border-radius:24px; padding:40px;
            box-shadow:0 20px 60px rgba(0,0,0,0.4);
        }
        .auth-title { font-family:var(--font-d); font-size:22px; font-weight:700; color:#fff; margin-bottom:6px; }
        .auth-desc  { font-size:14px; color:var(--muted); margin-bottom:28px; }

        .alert { padding:12px 16px; border-radius:10px; font-size:14px; margin-bottom:20px; display:flex; align-items:flex-start; gap:10px; border:1px solid; }
        .alert-danger  { background:rgba(239,68,68,0.08);  border-color:var(--danger);  color:#fca5a5; }
        .alert-success { background:rgba(16,185,129,0.08); border-color:var(--success); color:#6ee7b7; }

        .form-row { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
        @media(max-width:480px) { .form-row { grid-template-columns:1fr; } }

        .form-group { margin-bottom:18px; }
        .form-label { display:block; font-size:13px; font-weight:500; color:var(--text); margin-bottom:7px; }
        .input-wrapper { position:relative; }
        .input-icon { position:absolute; left:14px; top:50%; transform:translateY(-50%); color:var(--muted); font-size:16px; pointer-events:none; }
        .form-control {
            width:100%; background:var(--surface); border:1px solid var(--border);
            border-radius:10px; padding:11px 14px 11px 40px;
            color:var(--text); font-family:var(--font-b); font-size:14px;
            transition:border-color .2s,box-shadow .2s;
        }
        .form-control:focus { outline:none; border-color:var(--accent); box-shadow:0 0 0 3px rgba(0,212,255,0.1); }
        .form-control::placeholder { color:var(--muted); }
        .form-control.is-invalid { border-color:var(--danger); }
        .invalid-feedback { font-size:12px; color:var(--danger); margin-top:4px; }
        .toggle-password { position:absolute; right:14px; top:50%; transform:translateY(-50%); color:var(--muted); cursor:pointer; font-size:16px; }
        .toggle-password:hover { color:var(--text); }

        /* Indicateur force mot de passe */
        .pwd-strength {
            display:flex; gap:4px; margin-top:8px;
        }
        .pwd-bar {
            height:3px; flex:1; border-radius:2px;
            background:var(--border); transition:background 0.3s;
        }
        .pwd-bar.weak   { background:var(--danger); }
        .pwd-bar.medium { background:var(--warning, #f59e0b); }
        .pwd-bar.strong { background:var(--success); }

        .btn-submit {
            width:100%; padding:14px; border-radius:10px;
            background:var(--accent); color:#060a12;
            font-family:var(--font-d); font-size:15px; font-weight:700;
            border:none; cursor:pointer; transition:all .25s ease;
            display:flex; align-items:center; justify-content:center; gap:8px; margin-top:4px;
        }
        .btn-submit:hover { background:#00b8d9; box-shadow:0 0 30px rgba(0,212,255,0.3); transform:translateY(-1px); }

        .auth-footer { text-align:center; margin-top:24px; font-size:14px; color:var(--muted); }
        .auth-footer a { color:var(--accent); text-decoration:none; font-weight:500; }
        .auth-footer a:hover { text-decoration:underline; }

        /* Note validation admin */
        .info-note {
            background:rgba(0,212,255,0.06); border:1px solid rgba(0,212,255,0.15);
            border-radius:10px; padding:14px 16px;
            font-size:13px; color:var(--accent); margin-bottom:24px;
            display:flex; align-items:flex-start; gap:10px;
        }
    </style>
</head>
<body>
<div class="auth-container">

    <div class="auth-logo">
        <a href="{{ route('home') }}" class="logo-link">
            <div class="logo-icon"><i class="ri-links-line"></i></div>
            <span class="logo-text">LinkPulse</span>
        </a>
    </div>

    <div class="auth-card">
        <h1 class="auth-title">Créer un compte</h1>
        <p class="auth-desc">Remplissez le formulaire pour rejoindre LinkPulse.</p>

        {{-- Note processus validation --}}
        <div class="info-note">
            <i class="ri-information-line" style="flex-shrink:0;font-size:16px;"></i>
            Après inscription, votre compte sera examiné par un administrateur. Vous recevrez un email de confirmation dès validation.
        </div>

        @if(session('error'))
            <div class="alert alert-danger"><i class="ri-error-warning-line"></i>{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <i class="ri-error-warning-line"></i>
                <ul style="margin:0;padding-left:14px;">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.post') }}">
            @csrf

            {{-- Nom & Prénom --}}
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="first_name">Prénom <span style="color:var(--danger)">*</span></label>
                    <div class="input-wrapper">
                        <i class="ri-user-line input-icon"></i>
                        <input type="text" name="first_name" id="first_name"
                               class="form-control {{ $errors->has('first_name') ? 'is-invalid' : '' }}"
                               value="{{ old('first_name') }}" placeholder="Jean" required>
                    </div>
                    @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="last_name">Nom <span style="color:var(--danger)">*</span></label>
                    <div class="input-wrapper">
                        <i class="ri-user-line input-icon"></i>
                        <input type="text" name="last_name" id="last_name"
                               class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}"
                               value="{{ old('last_name') }}" placeholder="Dupont" required>
                    </div>
                    @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Email --}}
            <div class="form-group">
                <label class="form-label" for="email">Adresse email <span style="color:var(--danger)">*</span></label>
                <div class="input-wrapper">
                    <i class="ri-mail-line input-icon"></i>
                    <input type="email" name="email" id="email"
                           class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                           value="{{ old('email') }}" placeholder="jean@exemple.com" required>
                </div>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Téléphone & Localisation --}}
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="phone">Téléphone <span style="color:var(--danger)">*</span></label>
                    <div class="input-wrapper">
                        <i class="ri-phone-line input-icon"></i>
                        <input type="text" name="phone" id="phone"
                               class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                               value="{{ old('phone') }}" placeholder="+237 6XX XXX XXX" required>
                    </div>
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="location">Localisation <span style="color:var(--danger)">*</span></label>
                    <div class="input-wrapper">
                        <i class="ri-map-pin-line input-icon"></i>
                        <input type="text" name="location" id="location"
                               class="form-control {{ $errors->has('location') ? 'is-invalid' : '' }}"
                               value="{{ old('location') }}" placeholder="Douala, Cameroun" required>
                    </div>
                    @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Mot de passe --}}
            <div class="form-group">
                <label class="form-label" for="password">Mot de passe <span style="color:var(--danger)">*</span></label>
                <div class="input-wrapper">
                    <i class="ri-lock-line input-icon"></i>
                    <input type="password" name="password" id="password"
                           class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                           placeholder="Minimum 8 caractères" required>
                    <i class="ri-eye-off-line toggle-password" id="toggle-pwd"></i>
                </div>
                <div class="pwd-strength" id="pwd-strength">
                    <div class="pwd-bar" id="bar1"></div>
                    <div class="pwd-bar" id="bar2"></div>
                    <div class="pwd-bar" id="bar3"></div>
                    <div class="pwd-bar" id="bar4"></div>
                </div>
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Confirmation mot de passe --}}
            <div class="form-group">
                <label class="form-label" for="password_confirmation">Confirmer le mot de passe <span style="color:var(--danger)">*</span></label>
                <div class="input-wrapper">
                    <i class="ri-lock-check-line input-icon"></i>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="form-control" placeholder="Répétez le mot de passe" required>
                    <i class="ri-eye-off-line toggle-password" id="toggle-pwd2"></i>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <i class="ri-user-add-line"></i> Créer mon compte
            </button>
        </form>
    </div>

    <p class="auth-footer">
        Déjà un compte ? <a href="{{ route('login') }}">Se connecter</a>
    </p>
</div>

<script>
// Toggle visibilité mot de passe
function togglePwd(btnId, inputId) {
    document.getElementById(btnId).addEventListener('click', function() {
        const i = document.getElementById(inputId);
        const h = i.type === 'password';
        i.type = h ? 'text' : 'password';
        this.className = h ? 'ri-eye-line toggle-password' : 'ri-eye-off-line toggle-password';
    });
}
togglePwd('toggle-pwd', 'password');
togglePwd('toggle-pwd2', 'password_confirmation');

// Indicateur de force du mot de passe
document.getElementById('password').addEventListener('input', function() {
    const val = this.value;
    let score = 0;
    if (val.length >= 8)          score++;
    if (/[A-Z]/.test(val))        score++;
    if (/[0-9]/.test(val))        score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    const bars  = ['bar1','bar2','bar3','bar4'];
    const level = score === 0 ? '' : score <= 1 ? 'weak' : score <= 3 ? 'medium' : 'strong';

    bars.forEach((id, i) => {
        const el = document.getElementById(id);
        el.className = 'pwd-bar';
        if (i < score && level) el.classList.add(level);
    });
});
</script>
</body>
</html>
