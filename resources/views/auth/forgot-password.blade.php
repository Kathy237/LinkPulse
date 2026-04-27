{{-- resources/views/auth/forgot-password.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié — LinkPulse</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:opsz,wght@9..40,400;9..40,500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        :root{--bg:#060a12;--card:#0e1420;--border:#1e2a40;--accent:#00d4ff;--text:#e2e8f0;--muted:#64748b;--danger:#ef4444;--success:#10b981;--font-d:'Syne',sans-serif;--font-b:'DM Sans',sans-serif;}
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:var(--font-b);background:var(--bg);color:var(--text);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px;}
        body::before{content:'';position:fixed;inset:0;z-index:0;background:radial-gradient(ellipse 60% 50% at 30% 40%,rgba(0,212,255,0.05),transparent);}
        .container{position:relative;z-index:1;width:100%;max-width:420px;}
        .logo-center{text-align:center;margin-bottom:36px;}
        .logo-icon{width:52px;height:52px;background:linear-gradient(135deg,var(--accent),#7c3aed);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:24px;color:#fff;margin:0 auto 10px;}
        .logo-text{font-family:var(--font-d);font-size:26px;font-weight:800;color:#fff;text-decoration:none;}
        .card{background:var(--card);border:1px solid var(--border);border-radius:24px;padding:40px;box-shadow:0 20px 60px rgba(0,0,0,0.4);}
        .card-title{font-family:var(--font-d);font-size:21px;font-weight:700;color:#fff;margin-bottom:8px;}
        .card-desc{font-size:14px;color:var(--muted);margin-bottom:28px;line-height:1.6;}
        .alert{padding:12px 16px;border-radius:10px;font-size:14px;margin-bottom:20px;display:flex;align-items:flex-start;gap:10px;border:1px solid;}
        .alert-danger{background:rgba(239,68,68,0.08);border-color:var(--danger);color:#fca5a5;}
        .alert-success{background:rgba(16,185,129,0.08);border-color:var(--success);color:#6ee7b7;}
        .form-group{margin-bottom:20px;}
        .form-label{display:block;font-size:13px;font-weight:500;color:var(--text);margin-bottom:7px;}
        .input-wrapper{position:relative;}
        .input-icon{position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:16px;pointer-events:none;}
        .form-control{width:100%;background:#0b1120;border:1px solid var(--border);border-radius:10px;padding:12px 14px 12px 40px;color:var(--text);font-family:var(--font-b);font-size:14px;transition:border-color .2s,box-shadow .2s;}
        .form-control:focus{outline:none;border-color:var(--accent);box-shadow:0 0 0 3px rgba(0,212,255,0.1);}
        .form-control::placeholder{color:var(--muted);}
        .btn-submit{width:100%;padding:14px;border-radius:10px;background:var(--accent);color:#060a12;font-family:var(--font-d);font-size:15px;font-weight:700;border:none;cursor:pointer;transition:all .25s;display:flex;align-items:center;justify-content:center;gap:8px;}
        .btn-submit:hover{background:#00b8d9;box-shadow:0 0 30px rgba(0,212,255,0.3);transform:translateY(-1px);}
        .back-link{display:flex;align-items:center;gap:6px;margin-top:24px;font-size:14px;color:var(--muted);text-decoration:none;justify-content:center;transition:color .2s;}
        .back-link:hover{color:var(--text);}
    </style>
</head>
<body>
<div class="container">
    <div class="logo-center">
        <div class="logo-icon"><i class="ri-links-line"></i></div>
        <a href="{{ route('home') }}" class="logo-text">LinkPulse</a>
    </div>

    <div class="card">
        <h1 class="card-title">Mot de passe oublié ?</h1>
        <p class="card-desc">Entrez votre adresse email. Nous vous enverrons un code de vérification pour réinitialiser votre mot de passe.</p>

        @if(session('error'))
            <div class="alert alert-danger"><i class="ri-error-warning-line"></i>{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="alert alert-success"><i class="ri-checkbox-circle-line"></i>{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="form-group">
                <label class="form-label" for="email">Adresse email</label>
                <div class="input-wrapper">
                    <i class="ri-mail-line input-icon"></i>
                    <input type="email" name="email" id="email" class="form-control"
                           value="{{ old('email') }}" placeholder="votre@email.com" required>
                </div>
            </div>
            <button type="submit" class="btn-submit">
                <i class="ri-send-plane-line"></i> Envoyer le code
            </button>
        </form>
    </div>

    <a href="{{ route('login') }}" class="back-link">
        <i class="ri-arrow-left-line"></i> Retour à la connexion
    </a>
</div>
</body>
</html>
