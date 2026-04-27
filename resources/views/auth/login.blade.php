<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — LinkPulse</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:opsz,wght@9..40,400;9..40,500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        :root {
            --bg: #060a12; --surface: #0b1120; --card: #0e1420;
            --border: #1e2a40; --accent: #00d4ff; --accent2: #7c3aed;
            --text: #e2e8f0; --muted: #64748b;
            --danger: #ef4444; --success: #10b981;
            --font-d: 'Syne',sans-serif; --font-b: 'DM Sans',sans-serif;
        }
        *, *::before, *::after { box-sizing:border-box; margin:0; padding:0; }
        body {
            font-family: var(--font-b);
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Fond dégradé animé */
        body::before {
            content:'';
            position:fixed; inset:0; z-index:0;
            background:
                radial-gradient(ellipse 60% 50% at 20% 30%, rgba(0,212,255,0.06), transparent),
                radial-gradient(ellipse 60% 50% at 80% 70%, rgba(124,58,237,0.06), transparent);
        }

        .auth-container {
            position: relative; z-index:1;
            width: 100%; max-width: 440px;
            padding: 20px;
        }

        /* Logo centré */
        .auth-logo {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo-link {
            text-decoration:none;
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }

        .logo-icon {
            width: 56px; height: 56px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 26px; color: #fff;
            box-shadow: 0 8px 32px rgba(0,212,255,0.25);
        }

        .logo-text {
            font-family: var(--font-d);
            font-size: 28px;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.5px;
        }

        .auth-subtitle {
            font-size: 14px;
            color: var(--muted);
            margin-top: 4px;
        }

        /* Carte formulaire */
        .auth-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.4);
        }

        .auth-title {
            font-family: var(--font-d);
            font-size: 22px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 6px;
        }

        .auth-desc {
            font-size: 14px;
            color: var(--muted);
            margin-bottom: 32px;
        }

        /* Flash */
        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 14px;
            margin-bottom: 20px;
            display: flex; align-items: flex-start; gap: 10px;
            border: 1px solid;
        }
        .alert-danger  { background:rgba(239,68,68,0.08);  border-color:var(--danger);  color:#fca5a5; }
        .alert-success { background:rgba(16,185,129,0.08); border-color:var(--success); color:#6ee7b7; }

        /* Champs */
        .form-group { margin-bottom: 20px; }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--text);
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px; top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-size: 17px;
            pointer-events: none;
        }

        .form-control {
            width: 100%;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 12px 14px 12px 42px;
            color: var(--text);
            font-family: var(--font-b);
            font-size: 14px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(0,212,255,0.1);
        }

        .form-control::placeholder { color: var(--muted); }

        .form-control.is-invalid { border-color: var(--danger); }

        .invalid-feedback {
            font-size: 12px;
            color: var(--danger);
            margin-top: 4px;
        }

        /* Toggle mot de passe */
        .toggle-password {
            position: absolute;
            right: 14px; top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            cursor: pointer;
            font-size: 17px;
            transition: color 0.2s;
        }
        .toggle-password:hover { color: var(--text); }

        /* Lien "Mot de passe oublié" */
        .forgot-link {
            display: block;
            text-align: right;
            font-size: 12px;
            color: var(--accent);
            text-decoration: none;
            margin-top: 6px;
        }
        .forgot-link:hover { text-decoration: underline; }

        /* Bouton principal */
        .btn-submit {
            width: 100%;
            padding: 14px;
            border-radius: 10px;
            background: var(--accent);
            color: #060a12;
            font-family: var(--font-d);
            font-size: 15px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: all 0.25s ease;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            margin-top: 8px;
        }

        .btn-submit:hover {
            background: #00b8d9;
            box-shadow: 0 0 30px rgba(0,212,255,0.3);
            transform: translateY(-1px);
        }

        .btn-submit:active { transform: translateY(0); }

        /* Lien d'inscription */
        .auth-footer {
            text-align: center;
            margin-top: 28px;
            font-size: 14px;
            color: var(--muted);
        }

        .auth-footer a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 500;
        }

        .auth-footer a:hover { text-decoration: underline; }

        /* Divider */
        .divider {
            display: flex; align-items: center; gap: 12px;
            margin: 20px 0;
            color: var(--muted);
            font-size: 12px;
        }
        .divider::before, .divider::after {
            content:''; flex:1;
            height:1px; background: var(--border);
        }
    </style>
</head>
<body>
<div class="auth-container">

    {{-- Logo --}}
    <div class="auth-logo">
        <a href="{{ route('home') }}" class="logo-link">
            <div class="logo-icon"><i class="ri-links-line"></i></div>
            <span class="logo-text">LinkPulse</span>
        </a>
        <p class="auth-subtitle">La plateforme NFC intelligente</p>
    </div>

    {{-- Carte de connexion --}}
    <div class="auth-card">
        <h1 class="auth-title">Bon retour !</h1>
        <p class="auth-desc">Connectez-vous à votre espace LinkPulse.</p>

        {{-- Flash --}}
        @if(session('error'))
            <div class="alert alert-danger"><i class="ri-error-warning-line"></i>{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="alert alert-success"><i class="ri-checkbox-circle-line"></i>{{ session('success') }}</div>
        @endif

        {{-- Formulaire --}}
        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            {{-- Email --}}
            <div class="form-group">
                <label class="form-label" for="email">Adresse email</label>
                <div class="input-wrapper">
                    <i class="ri-mail-line input-icon"></i>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                        value="{{ old('email') }}"
                        placeholder="votre@email.com"
                        autocomplete="email"
                        required
                    >
                </div>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Mot de passe --}}
            <div class="form-group">
                <label class="form-label" for="password">Mot de passe</label>
                <div class="input-wrapper">
                    <i class="ri-lock-line input-icon"></i>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                        placeholder="••••••••"
                        autocomplete="current-password"
                        required
                    >
                    <i class="ri-eye-off-line toggle-password" id="toggle-pwd"></i>
                </div>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <a href="{{ route('password.request') }}" class="forgot-link">Mot de passe oublié ?</a>
            </div>

            <button type="submit" class="btn-submit">
                <i class="ri-login-box-line"></i> Se connecter
            </button>
        </form>
    </div>

    {{-- Lien inscription --}}
    <p class="auth-footer">
        Pas encore de compte ?
        <a href="{{ route('register') }}">Créer un compte</a>
    </p>
    <p class="auth-footer" style="margin-top:8px;">
        <a href="{{ route('visitor.home') }}" style="color:var(--muted);">
            <i class="ri-arrow-left-line"></i> Accès visiteur
        </a>
    </p>
</div>

<script>
// Toggle visibilité mot de passe
document.getElementById('toggle-pwd').addEventListener('click', function () {
    const input = document.getElementById('password');
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    this.className = isHidden ? 'ri-eye-line toggle-password' : 'ri-eye-off-line toggle-password';
});
</script>
</body>
</html>
