<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $portfolio['title'] ?? 'Portfolio' }} — LinkPulse</title>
    <meta name="description" content="{{ $portfolio['description'] ?? '' }}">

    {{-- Open Graph --}}
    <meta property="og:title"       content="{{ $portfolio['title'] ?? 'Portfolio' }}">
    <meta property="og:description" content="{{ $portfolio['description'] ?? '' }}">
    @if(!empty($portfolio['photo_url']))
    <meta property="og:image"       content="{{ $portfolio['photo_url'] }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">

    <style>
        :root {
            --bg:#060a12; --surface:#0b1120; --card:#0e1420;
            --border:#1e2a40; --accent:#00d4ff; --accent2:#7c3aed;
            --text:#e2e8f0; --muted:#64748b; --success:#10b981;
            --font-d:'Syne',sans-serif; --font-b:'DM Sans',sans-serif;
        }
        *, *::before, *::after { box-sizing:border-box; margin:0; padding:0; }
        html { scroll-behavior:smooth; }
        body { font-family:var(--font-b); background:var(--bg); color:var(--text); min-height:100vh; }

        /* Fond dégradé animé */
        body::before {
            content:'';
            position:fixed; inset:0; z-index:0;
            background:
                radial-gradient(ellipse 50% 60% at 30% 20%, rgba(0,212,255,0.05), transparent),
                radial-gradient(ellipse 50% 60% at 70% 80%, rgba(124,58,237,0.05), transparent);
            pointer-events:none;
        }

        .container {
            position:relative; z-index:1;
            max-width:680px;
            margin:0 auto;
            padding:40px 20px 80px;
        }

        /* ── En-tête profil ── */
        .profile-header {
            text-align:center;
            margin-bottom:36px;
            animation: fadeInDown 0.6s ease both;
        }

        @keyframes fadeInDown {
            from{opacity:0;transform:translateY(-20px);}
            to{opacity:1;transform:translateY(0);}
        }

        .profile-photo {
            width:100px; height:100px;
            border-radius:50%;
            object-fit:cover;
            border:3px solid var(--accent);
            box-shadow:0 0 30px rgba(0,212,255,0.2);
            margin-bottom:16px;
        }

        .profile-avatar {
            width:100px; height:100px;
            border-radius:50%;
            background:linear-gradient(135deg,var(--accent),var(--accent2));
            display:flex;align-items:center;justify-content:center;
            font-family:var(--font-d);font-size:40px;font-weight:800;color:#fff;
            margin:0 auto 16px;
            box-shadow:0 0 30px rgba(0,212,255,0.2);
        }

        .profile-name {
            font-family:var(--font-d);
            font-size:26px;
            font-weight:800;
            letter-spacing:-0.5px;
            color:#fff;
            margin-bottom:8px;
        }

        .profile-desc {
            font-size:15px;
            color:var(--muted);
            line-height:1.7;
            max-width:480px;
            margin:0 auto;
        }

        /* ── Boutons d'action ── */
        .action-buttons {
            display:flex;
            justify-content:center;
            gap:10px;
            flex-wrap:wrap;
            margin-bottom:32px;
            animation: fadeInUp 0.6s ease 0.1s both;
        }

        @keyframes fadeInUp {
            from{opacity:0;transform:translateY(20px);}
            to{opacity:1;transform:translateY(0);}
        }

        .action-btn {
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding:11px 22px;
            border-radius:10px;
            text-decoration:none;
            font-size:14px;
            font-weight:500;
            transition:all 0.2s;
            border:1px solid var(--border);
            background:var(--card);
            color:var(--text);
        }

        .action-btn:hover {
            border-color:var(--accent);
            color:var(--accent);
            box-shadow:0 0 20px rgba(0,212,255,0.1);
            transform:translateY(-2px);
        }

        .action-btn.primary {
            background:var(--accent);
            color:#060a12;
            border-color:var(--accent);
            font-weight:600;
        }

        .action-btn.primary:hover {
            background:#00b8d9;
            color:#060a12;
        }

        /* ── Sections ── */
        .section {
            background:var(--card);
            border:1px solid var(--border);
            border-radius:16px;
            margin-bottom:20px;
            overflow:hidden;
            animation:fadeInUp 0.6s ease 0.2s both;
        }

        .section-header {
            padding:16px 20px;
            font-family:var(--font-d);
            font-size:14px;
            font-weight:700;
            color:#fff;
            border-bottom:1px solid var(--border);
            display:flex;
            align-items:center;
            gap:8px;
        }

        .section-body { padding:20px; }

        /* ── Réseaux sociaux ── */
        .social-links {
            display:flex;
            justify-content:center;
            flex-wrap:wrap;
            gap:14px;
            margin-bottom:24px;
            animation:fadeInUp 0.6s ease 0.3s both;
        }

        .social-link {
            width:46px; height:46px;
            border-radius:12px;
            background:var(--card);
            border:1px solid var(--border);
            display:flex;align-items:center;justify-content:center;
            font-size:20px;
            color:var(--muted);
            text-decoration:none;
            transition:all 0.2s;
        }

        .social-link:hover {
            border-color:var(--accent);
            color:var(--accent);
            transform:translateY(-3px);
            box-shadow:0 8px 20px rgba(0,0,0,0.2);
        }

        /* ── Projets ── */
        .project-card {
            background:var(--surface);
            border:1px solid var(--border);
            border-radius:10px;
            padding:14px 16px;
            margin-bottom:10px;
            transition:all 0.2s;
        }

        .project-card:hover {
            border-color:rgba(0,212,255,0.2);
            transform:translateX(4px);
        }

        .project-title {
            font-size:14px;
            font-weight:600;
            color:#fff;
            margin-bottom:4px;
        }

        .project-desc {
            font-size:13px;
            color:var(--muted);
            margin-bottom:8px;
            line-height:1.6;
        }

        /* ── Liens customs ── */
        .custom-link {
            display:flex;
            align-items:center;
            gap:12px;
            padding:12px 14px;
            background:var(--surface);
            border:1px solid var(--border);
            border-radius:10px;
            text-decoration:none;
            color:var(--text);
            font-size:14px;
            margin-bottom:8px;
            transition:all 0.2s;
        }

        .custom-link:hover {
            border-color:rgba(0,212,255,0.2);
            color:var(--accent);
        }

        .custom-link i { color:var(--accent); font-size:18px; flex-shrink:0; }

        /* ── PoweredBy + Signaler ── */
        .footer-actions {
            text-align:center;
            margin-top:40px;
        }

        .report-link {
            font-size:12px;
            color:var(--muted);
            text-decoration:none;
            transition:color 0.2s;
        }

        .report-link:hover { color:var(--color-danger, #ef4444); }

        .powered-by {
            display:inline-flex;align-items:center;gap:6px;
            font-size:12px;color:var(--muted);
            text-decoration:none;
            margin-bottom:12px;
        }

        .powered-by span { color:var(--accent); font-weight:600; }

        /* Icônes réseaux sociaux par plateforme */
        .social-link[data-platform="linkedin"] { color:#0a66c2; border-color:rgba(10,102,194,0.2); }
        .social-link[data-platform="github"]   { color:#6e7681; }
        .social-link[data-platform="twitter"]  { color:#1d9bf0; }
        .social-link[data-platform="instagram"]{ color:#e1306c; }
        .social-link[data-platform="facebook"] { color:#1877f2; }
        .social-link[data-platform="youtube"]  { color:#ff0000; }
    </style>
</head>
<body>
<div class="container">

    {{-- ══ PHOTO + NOM + BIO ══ --}}
    <div class="profile-header">
        @if(!empty($portfolio['photo_url']))
            <img src="{{ $portfolio['photo_url'] }}" alt="Photo de profil" class="profile-photo">
        @else
            <div class="profile-avatar">
                {{ strtoupper(substr($portfolio['title'] ?? 'U', 0, 1)) }}
            </div>
        @endif

        <h1 class="profile-name">{{ $portfolio['title'] ?? 'Portfolio' }}</h1>

        @if(!empty($portfolio['description']))
        <p class="profile-desc">{{ $portfolio['description'] }}</p>
        @endif
    </div>

    {{-- ══ BOUTONS D'ACTION ══ --}}
    <div class="action-buttons">
        @if(!empty($portfolio['cv_url']))
        <a href="{{ route('public.cv', $portfolio['id']) }}" class="action-btn primary">
            <i class="ri-file-text-line"></i> CV
        </a>
        @endif

        @if(!empty($portfolio['projects']) && count($portfolio['projects']) > 0)
        <a href="#projets" class="action-btn">
            <i class="ri-code-s-slash-line"></i> Projets
        </a>
        @endif

        @if(!empty($portfolio['id']))
    <a href="{{ route('public.vcard', $portfolio['id']) }}" class="action-btn">
        <i class="ri-contacts-line"></i> vCard
    </a>
@else
    <span class="action-btn disabled" title="vCard non disponible">
        <i class="ri-contacts-line"></i> vCard
    </span>
        @endif

        @if(!empty($portfolio['portfolio_url']))
        <a href="{{ $portfolio['portfolio_url'] }}" target="_blank" class="action-btn">
            <i class="ri-global-line"></i> Portfolio
        </a>
        @endif
    </div>

    {{-- ══ RÉSEAUX SOCIAUX ══ --}}
    @if(!empty($portfolio['social_links']) && count($portfolio['social_links']) > 0)
    <div class="social-links">
        @foreach($portfolio['social_links'] as $sl)
        <a href="{{ $sl['url'] }}" target="_blank" rel="noopener"
           class="social-link"
           data-platform="{{ $sl['platform'] ?? 'link' }}"
           title="{{ ucfirst($sl['platform'] ?? 'Lien') }}">
            <i class="ri-{{ $sl['platform'] ?? 'link' }}-fill"></i>
        </a>
        @endforeach
    </div>
    @endif

    {{-- ══ PROJETS ══ --}}
    @if(!empty($portfolio['projects']) && count($portfolio['projects']) > 0)
    <div class="section" id="projets">
        <div class="section-header">
            <i class="ri-code-s-slash-line" style="color:var(--accent);"></i>
            Projets
        </div>
        <div class="section-body">
            @foreach($portfolio['projects'] as $project)
            <div class="project-card">
                <div class="project-title">{{ $project['title'] }}</div>
                @if(!empty($project['description']))
                <div class="project-desc">{{ $project['description'] }}</div>
                @endif
                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                    @if(!empty($project['url']))
                    <a href="{{ $project['url'] }}" target="_blank" rel="noopener"
                       style="font-size:12px;color:var(--accent);text-decoration:none;display:flex;align-items:center;gap:4px;">
                        <i class="ri-external-link-line"></i> Voir le projet
                    </a>
                    @endif
                    @if(!empty($project['github_url']))
                    <a href="{{ $project['github_url'] }}" target="_blank" rel="noopener"
                       style="font-size:12px;color:var(--muted);text-decoration:none;display:flex;align-items:center;gap:4px;">
                        <i class="ri-github-line"></i> GitHub
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ══ LIENS PERSONNALISÉS ══ --}}
    @if(!empty($portfolio['custom_links']) && count($portfolio['custom_links']) > 0)
    <div class="section">
        <div class="section-header">
            <i class="ri-links-line" style="color:var(--accent);"></i>
            Liens
        </div>
        <div class="section-body">
            @foreach($portfolio['custom_links'] as $cl)
            <a href="{{ $cl['url'] }}" target="_blank" rel="noopener" class="custom-link">
                <i class="ri-external-link-line"></i>
                {{ $cl['label'] }}
                <i class="ri-arrow-right-s-line" style="margin-left:auto;color:var(--muted);"></i>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ══ PIED DE PAGE ══ --}}
    <div class="footer-actions">
        <div>
            <a href="{{ route('home') }}" class="powered-by">
                Propulsé par <span>LinkPulse</span> <i class="ri-links-line"></i>
            </a>
        </div>
        <div>
            <a href="{{ route('visitor.report') }}?portfolio_url={{ urlencode(request()->url()) }}"
               class="report-link">
                <i class="ri-alarm-warning-line"></i> Signaler ce profil
            </a>
        </div>
    </div>

</div>
</body>
</html>
