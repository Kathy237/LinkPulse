{{-- resources/views/visitor/home.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace visiteur — LinkPulse</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:opsz,wght@9..40,400;9..40,500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        :root{--bg:#060a12;--surface:#0b1120;--card:#0e1420;--border:#1e2a40;--accent:#00d4ff;--accent2:#7c3aed;--text:#e2e8f0;--muted:#64748b;--font-d:'Syne',sans-serif;--font-b:'DM Sans',sans-serif;}
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:var(--font-b);background:var(--bg);color:var(--text);min-height:100vh;}
        body::before{content:'';position:fixed;inset:0;z-index:0;background:radial-gradient(ellipse 50% 60% at 30% 20%,rgba(0,212,255,0.04),transparent);}

        nav{position:sticky;top:0;z-index:100;background:rgba(6,10,18,0.9);backdrop-filter:blur(20px);border-bottom:1px solid rgba(255,255,255,0.05);padding:16px 32px;display:flex;align-items:center;justify-content:space-between;}
        .logo{display:flex;align-items:center;gap:10px;text-decoration:none;}
        .logo-icon{width:36px;height:36px;background:linear-gradient(135deg,var(--accent),var(--accent2));border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:17px;color:#fff;}
        .logo-text{font-family:var(--font-d);font-size:20px;font-weight:800;color:#fff;}
        .btn-login{padding:8px 20px;border-radius:8px;background:var(--accent);color:#060a12;font-weight:600;font-size:14px;text-decoration:none;}

        .container{position:relative;z-index:1;max-width:700px;margin:0 auto;padding:48px 20px;}
        .page-title{font-family:var(--font-d);font-size:28px;font-weight:800;color:#fff;margin-bottom:8px;}
        .page-desc{font-size:15px;color:var(--muted);margin-bottom:36px;}

        .card{background:var(--card);border:1px solid var(--border);border-radius:16px;margin-bottom:20px;}
        .card-header{padding:18px 22px;font-family:var(--font-d);font-size:14px;font-weight:700;color:#fff;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:8px;}
        .card-body{padding:22px;}

        .history-item{display:flex;align-items:center;gap:14px;padding:12px;background:var(--surface);border-radius:10px;margin-bottom:8px;text-decoration:none;color:var(--text);transition:all .2s;}
        .history-item:hover{border:1px solid rgba(0,212,255,0.15);transform:translateX(4px);}
        .history-icon{width:38px;height:38px;border-radius:10px;background:rgba(0,212,255,0.08);display:flex;align-items:center;justify-content:center;font-size:16px;color:var(--accent);flex-shrink:0;}
        .history-title{font-size:14px;font-weight:500;color:#fff;}
        .history-date{font-size:12px;color:var(--muted);}

        .empty-state{text-align:center;padding:32px 0;color:var(--muted);}
        .empty-state i{font-size:40px;display:block;margin-bottom:12px;}

        .form-group{margin-bottom:18px;}
        .form-label{display:block;font-size:13px;font-weight:500;color:var(--text);margin-bottom:7px;}
        .form-control{width:100%;background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:11px 14px;color:var(--text);font-family:var(--font-b);font-size:14px;transition:border-color .2s;}
        .form-control:focus{outline:none;border-color:var(--accent);box-shadow:0 0 0 3px rgba(0,212,255,0.1);}
        .form-control::placeholder{color:var(--muted);}
        textarea.form-control{resize:vertical;min-height:100px;}
        .btn-submit{padding:12px 28px;border-radius:10px;background:var(--accent);color:#060a12;font-family:var(--font-d);font-size:14px;font-weight:700;border:none;cursor:pointer;transition:all .25s;display:inline-flex;align-items:center;gap:8px;}
        .btn-submit:hover{background:#00b8d9;box-shadow:0 0 25px rgba(0,212,255,0.25);}

        .alert{padding:12px 16px;border-radius:10px;font-size:14px;margin-bottom:20px;display:flex;align-items:flex-start;gap:10px;border:1px solid;}
        .alert-success{background:rgba(16,185,129,0.08);border-color:#10b981;color:#6ee7b7;}
        .alert-danger{background:rgba(239,68,68,0.08);border-color:#ef4444;color:#fca5a5;}

        .register-banner{background:rgba(0,212,255,0.05);border:1px solid rgba(0,212,255,0.15);border-radius:12px;padding:18px 22px;margin-bottom:28px;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;}
        .register-banner p{font-size:14px;color:var(--text);}
        .register-banner a{padding:9px 22px;border-radius:8px;background:var(--accent);color:#060a12;font-weight:600;font-size:14px;text-decoration:none;white-space:nowrap;}
    </style>
</head>
<body>

<nav>
    <a href="{{ route('home') }}" class="logo">
        <div class="logo-icon"><i class="ri-links-line"></i></div>
        <span class="logo-text">LinkPulse</span>
    </a>
    <a href="{{ route('login') }}" class="btn-login">
        <i class="ri-login-box-line"></i> Se connecter
    </a>
</nav>

<div class="container">

    <h1 class="page-title">Espace visiteur</h1>
    <p class="page-desc">Retrouvez l'historique de vos tags NFC et signalez les profils frauduleux.</p>

    {{-- Bannière inscription --}}
    <div class="register-banner">
        <p><i class="ri-user-add-line" style="color:var(--accent);"></i> Vous souhaitez créer vos propres cartes NFC ?</p>
        <a href="{{ route('register') }}">S'inscrire gratuitement</a>
    </div>

    @if(session('success'))
    <div class="alert alert-success"><i class="ri-checkbox-circle-line"></i>{{ session('success') }}</div>
    @endif

    {{-- ══ HISTORIQUE DES TAGS ══ --}}
    <div class="card">
        <div class="card-header">
            <i class="ri-history-line" style="color:var(--accent);"></i>
            Mes derniers tags NFC
        </div>
        <div class="card-body">
            @if(!empty($history) && count($history) > 0)
            @foreach($history as $item)
            <a href="{{ isset($item['portfolio']['slug']) ? route('public.portfolio', $item['portfolio']['slug']) : '#' }}"
               class="history-item">
                <div class="history-icon"><i class="ri-wifi-line"></i></div>
                <div style="flex:1;min-width:0;">
                    <div class="history-title" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                        {{ $item['portfolio']['title'] ?? 'Portfolio' }}
                    </div>
                    <div class="history-date">
                        {{ isset($item['visited_at']) ? \Carbon\Carbon::parse($item['visited_at'])->format('d/m/Y à H:i') : '' }}
                        @if(!empty($item['location'])) · {{ $item['location'] }}@endif
                    </div>
                </div>
                <i class="ri-arrow-right-s-line" style="color:var(--muted);"></i>
            </a>
            @endforeach
            @else
            <div class="empty-state">
                <i class="ri-wifi-off-line"></i>
                <p>Aucun tag NFC dans votre historique.</p>
                <p style="font-size:13px;margin-top:4px;">Approchez votre téléphone d'une carte LinkPulse NFC pour commencer.</p>
            </div>
            @endif
        </div>
    </div>

    {{-- ══ SIGNALER ══ --}}
    <div class="card">
        <div class="card-header">
            <i class="ri-alarm-warning-line" style="color:#ef4444;"></i>
            Signaler un profil frauduleux
        </div>
        <div class="card-body">
            <p style="font-size:14px;color:var(--muted);margin-bottom:20px;line-height:1.7;">
                Vous avez été victime d'une fraude via un profil LinkPulse ? Signalez-le à l'administrateur.
            </p>
            <a href="{{ route('visitor.report') }}" style="display:inline-flex;align-items:center;gap:8px;padding:10px 22px;border-radius:10px;background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);color:#fca5a5;text-decoration:none;font-size:14px;font-weight:500;transition:all .2s;">
                <i class="ri-alarm-warning-line"></i> Faire un signalement
            </a>
        </div>
    </div>

</div>
</body>
</html>
