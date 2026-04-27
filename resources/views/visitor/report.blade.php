<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signaler un profil — LinkPulse</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:opsz,wght@9..40,400;9..40,500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        :root{--bg:#060a12;--surface:#0b1120;--card:#0e1420;--border:#1e2a40;--accent:#00d4ff;--accent2:#7c3aed;--text:#e2e8f0;--muted:#64748b;--danger:#ef4444;--success:#10b981;--font-d:'Syne',sans-serif;--font-b:'DM Sans',sans-serif;}
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:var(--font-b);background:var(--bg);color:var(--text);min-height:100vh;padding:40px 20px;}
        body::before{content:'';position:fixed;inset:0;z-index:0;background:radial-gradient(ellipse 50% 50% at 50% 30%,rgba(239,68,68,0.04),transparent);}
        .container{position:relative;z-index:1;max-width:560px;margin:0 auto;}
        .back-link{display:inline-flex;align-items:center;gap:6px;color:var(--muted);text-decoration:none;font-size:14px;margin-bottom:28px;transition:color .2s;}
        .back-link:hover{color:var(--text);}
        .page-header{margin-bottom:32px;}
        .page-header-icon{width:56px;height:56px;border-radius:14px;background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.2);display:flex;align-items:center;justify-content:center;font-size:24px;color:var(--danger);margin-bottom:16px;}
        .page-title{font-family:var(--font-d);font-size:24px;font-weight:800;color:#fff;margin-bottom:8px;}
        .page-desc{font-size:14px;color:var(--muted);line-height:1.7;}
        .card{background:var(--card);border:1px solid var(--border);border-radius:20px;padding:32px;}
        .alert{padding:12px 16px;border-radius:10px;font-size:14px;margin-bottom:20px;display:flex;align-items:flex-start;gap:10px;border:1px solid;}
        .alert-success{background:rgba(16,185,129,0.08);border-color:var(--success);color:#6ee7b7;}
        .alert-danger{background:rgba(239,68,68,0.08);border-color:var(--danger);color:#fca5a5;}
        .form-group{margin-bottom:18px;}
        .form-label{display:block;font-size:13px;font-weight:500;color:var(--text);margin-bottom:7px;}
        .form-control{width:100%;background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:11px 14px;color:var(--text);font-family:var(--font-b);font-size:14px;transition:border-color .2s;}
        .form-control:focus{outline:none;border-color:var(--danger);box-shadow:0 0 0 3px rgba(239,68,68,0.1);}
        .form-control::placeholder{color:var(--muted);}
        textarea.form-control{resize:vertical;min-height:120px;}
        .form-control.is-invalid{border-color:var(--danger);}
        .invalid-feedback{font-size:12px;color:var(--danger);margin-top:4px;}
        .required{color:var(--danger);}
        .or-divider{display:flex;align-items:center;gap:10px;margin:8px 0;color:var(--muted);font-size:12px;}
        .or-divider::before,.or-divider::after{content:'';flex:1;height:1px;background:var(--border);}
        .btn-submit{width:100%;padding:14px;border-radius:10px;background:var(--danger);color:#fff;font-family:var(--font-d);font-size:15px;font-weight:700;border:none;cursor:pointer;transition:all .25s;display:flex;align-items:center;justify-content:center;gap:8px;}
        .btn-submit:hover{background:#dc2626;box-shadow:0 0 25px rgba(239,68,68,0.25);}
        .info-box{background:rgba(245,158,11,0.05);border:1px solid rgba(245,158,11,0.15);border-radius:10px;padding:14px 16px;font-size:13px;color:#fcd34d;margin-bottom:24px;line-height:1.7;}
    </style>
</head>
<body>
<div class="container">

    <a href="{{ route('visitor.home') }}" class="back-link">
        <i class="ri-arrow-left-line"></i> Retour à l'espace visiteur
    </a>

    <div class="page-header">
        <div class="page-header-icon"><i class="ri-alarm-warning-line"></i></div>
        <h1 class="page-title">Signaler un profil frauduleux</h1>
        <p class="page-desc">
            Aidez-nous à protéger la communauté en signalant tout profil utilisant LinkPulse à des fins frauduleuses.
            L'administrateur examinera votre signalement.
        </p>
    </div>

    @if(session('success'))
    <div class="alert alert-success"><i class="ri-checkbox-circle-line"></i>{{ session('success') }}</div>
    @endif
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

    <div class="info-box">
        <i class="ri-information-line"></i>
        Fournissez au moins le <strong>lien du portfolio frauduleux</strong> OU <strong>l'adresse email</strong> de l'utilisateur concerné.
    </div>

    <div class="card">
        <form method="POST" action="{{ route('visitor.report.post') }}">
            @csrf

            {{-- Lien du portfolio --}}
            <div class="form-group">
                <label class="form-label" for="portfolio_url">
                    Lien du portfolio concerné
                </label>
                <input type="url" name="portfolio_url" id="portfolio_url"
                       class="form-control"
                       value="{{ old('portfolio_url', request('portfolio_url')) }}"
                       placeholder="https://linkpulse.app/p/...">
            </div>

            <div class="or-divider">ou</div>

            {{-- Email de l'utilisateur --}}
            <div class="form-group">
                <label class="form-label" for="reported_email">
                    Email de l'utilisateur frauduleux
                </label>
                <input type="email" name="reported_email" id="reported_email"
                       class="form-control"
                       value="{{ old('reported_email') }}"
                       placeholder="fraudeur@exemple.com">
            </div>

            <hr style="border:none;border-top:1px solid var(--border);margin:20px 0;">

            {{-- Description de la fraude --}}
            <div class="form-group">
                <label class="form-label" for="message">
                    Description de la fraude <span class="required">*</span>
                </label>
                <textarea name="message" id="message"
                          class="form-control {{ $errors->has('message') ? 'is-invalid' : '' }}"
                          placeholder="Décrivez la fraude dont vous avez été victime (minimum 20 caractères)..."
                          required>{{ old('message') }}</textarea>
                @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <hr style="border:none;border-top:1px solid var(--border);margin:20px 0;">

            {{-- Infos du signaleur (optionnel) --}}
            <div style="font-size:13px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:14px;">
                Vos informations (optionnel)
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:0;">
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Votre nom</label>
                    <input type="text" name="reporter_name" class="form-control"
                           value="{{ old('reporter_name') }}" placeholder="Anonyme">
                </div>
                <div class="form-group" style="margin-bottom:18px;">
                    <label class="form-label">Votre email</label>
                    <input type="email" name="reporter_email" class="form-control"
                           value="{{ old('reporter_email') }}" placeholder="votre@email.com">
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <i class="ri-send-plane-line"></i> Envoyer le signalement
            </button>
        </form>
    </div>
</div>
</body>
</html>
