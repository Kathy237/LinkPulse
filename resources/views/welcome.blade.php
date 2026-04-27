<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LinkPulse — Vos cartes NFC intelligentes</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">

    <style>
        :root {
            --bg:       #060a12;
            --surface:  #0b1120;
            --accent:   #00d4ff;
            --accent2:  #7c3aed;
            --text:     #e2e8f0;
            --muted:    #64748b;
            --font-d:   'Syne', sans-serif;
            --font-b:   'DM Sans', sans-serif;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; }

        body {
            font-family: var(--font-b);
            background: var(--bg);
            color: var(--text);
            overflow-x: hidden;
        }

        /* ═══════════════════════════════════
           CANVAS FOND PARTICULES
        ═══════════════════════════════════ */
        #canvas-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
        }

        /* ═══════════════════════════════════
           NAVBAR
        ═══════════════════════════════════ */
        nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            padding: 20px 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(6,10,18,0.7);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .logo-icon {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; color: #fff;
        }

        .logo-text {
            font-family: var(--font-d);
            font-size: 22px;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.5px;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 32px;
            list-style: none;
        }

        .nav-links a {
            color: var(--muted);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.2s;
        }

        .nav-links a:hover { color: var(--text); }

        .nav-cta {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-nav-login {
            padding: 9px 22px;
            border-radius: 8px;
            background: transparent;
            border: 1px solid rgba(255,255,255,0.15);
            color: var(--text);
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-nav-login:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        .btn-nav-register {
            padding: 9px 22px;
            border-radius: 8px;
            background: var(--accent);
            color: #060a12;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-nav-register:hover {
            background: #00b8d9;
            box-shadow: 0 0 25px rgba(0,212,255,0.35);
        }

        /* ═══════════════════════════════════
           HERO SECTION
        ═══════════════════════════════════ */
        .hero {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 120px 20px 60px;
        }

        .hero-inner {
            max-width: 800px;
        }

        /* Étiquette animée */
        .hero-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 18px;
            border-radius: 30px;
            background: rgba(0,212,255,0.08);
            border: 1px solid rgba(0,212,255,0.2);
            font-size: 13px;
            color: var(--accent);
            font-weight: 500;
            margin-bottom: 32px;
            animation: fadeInDown 0.8s ease both;
        }

        .hero-tag-dot {
            width: 6px; height: 6px;
            background: var(--accent);
            border-radius: 50%;
            animation: pulse 2s ease infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: 0.5; transform: scale(1.5); }
        }

        /* Titre principal */
        .hero-title {
            font-family: var(--font-d);
            font-size: clamp(44px, 7vw, 84px);
            font-weight: 800;
            line-height: 1.05;
            letter-spacing: -2px;
            color: #fff;
            margin-bottom: 24px;
            animation: fadeInUp 0.8s ease 0.1s both;
        }

        .hero-title .gradient-text {
            background: linear-gradient(90deg, var(--accent), var(--accent2), var(--accent));
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: shimmer 3s linear infinite;
        }

        @keyframes shimmer {
            0%   { background-position: 0% center; }
            100% { background-position: 200% center; }
        }

        .hero-subtitle {
            font-size: 18px;
            color: var(--muted);
            line-height: 1.7;
            max-width: 580px;
            margin: 0 auto 40px;
            animation: fadeInUp 0.8s ease 0.2s both;
        }

        .hero-actions {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            flex-wrap: wrap;
            animation: fadeInUp 0.8s ease 0.3s both;
        }

        .btn-hero-primary {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 32px;
            border-radius: 10px;
            background: var(--accent);
            color: #060a12;
            font-family: var(--font-d);
            font-size: 16px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.25s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-hero-primary::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.4s ease;
        }

        .btn-hero-primary:hover::before { left: 100%; }

        .btn-hero-primary:hover {
            box-shadow: 0 0 40px rgba(0,212,255,0.4);
            transform: translateY(-2px);
        }

        .btn-hero-secondary {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 32px;
            border-radius: 10px;
            background: transparent;
            border: 1px solid rgba(255,255,255,0.15);
            color: var(--text);
            font-size: 16px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.25s ease;
        }

        .btn-hero-secondary:hover {
            border-color: var(--accent);
            color: var(--accent);
            transform: translateY(-2px);
        }

        /* ═══════════════════════════════════
           CARTE NFC 3D FLOTTANTE
        ═══════════════════════════════════ */
        .hero-card-3d {
            position: relative;
            z-index: 1;
            padding: 80px 20px;
            display: flex;
            justify-content: center;
            animation: fadeInUp 1s ease 0.4s both;
        }

        .nfc-card-scene {
            perspective: 1200px;
        }

        .nfc-card {
            width: 360px;
            height: 220px;
            position: relative;
            transform-style: preserve-3d;
            animation: float3d 6s ease-in-out infinite;
            cursor: pointer;
        }

        @keyframes float3d {
            0%   { transform: rotateX(12deg) rotateY(-15deg) translateY(0px); }
            25%  { transform: rotateX(8deg)  rotateY(-10deg) translateY(-12px); }
            50%  { transform: rotateX(12deg) rotateY(-15deg) translateY(-6px); }
            75%  { transform: rotateX(16deg) rotateY(-20deg) translateY(-12px); }
            100% { transform: rotateX(12deg) rotateY(-15deg) translateY(0px); }
        }

        .nfc-card:hover {
            animation-play-state: paused;
        }

        .card-face {
            position: absolute;
            inset: 0;
            border-radius: 20px;
            padding: 28px;
            backface-visibility: hidden;
        }

        .card-front {
            background: linear-gradient(135deg, #0e1a2e 0%, #1a2a4a 50%, #0e1a2e 100%);
            border: 1px solid rgba(0,212,255,0.3);
            box-shadow:
                0 20px 60px rgba(0,0,0,0.5),
                0 0 0 1px rgba(0,212,255,0.1),
                inset 0 1px 0 rgba(255,255,255,0.05);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Effet holographique */
        .card-front::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 20px;
            background: linear-gradient(
                135deg,
                rgba(0,212,255,0.05) 0%,
                transparent 40%,
                rgba(124,58,237,0.05) 60%,
                transparent 100%
            );
        }

        .card-nfc-symbol {
            position: absolute;
            top: 20px; right: 24px;
            opacity: 0.5;
        }

        .card-nfc-symbol i { font-size: 28px; color: var(--accent); }

        .card-chip {
            width: 44px; height: 34px;
            background: linear-gradient(135deg, #d4af37, #c9a227);
            border-radius: 6px;
            position: relative;
        }

        .card-chip::before {
            content: '';
            position: absolute;
            top: 8px; left: 6px; right: 6px; bottom: 8px;
            border: 1px solid rgba(0,0,0,0.3);
            border-radius: 3px;
        }

        .card-name {
            font-family: var(--font-d);
            font-size: 18px;
            font-weight: 700;
            color: #fff;
            letter-spacing: 1px;
        }

        .card-title-role {
            font-size: 12px;
            color: var(--accent);
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .card-glow {
            position: absolute;
            width: 200px; height: 200px;
            background: radial-gradient(circle, rgba(0,212,255,0.15), transparent 70%);
            top: -40px; right: -40px;
            pointer-events: none;
        }

        /* Ombre portée 3D */
        .nfc-card-shadow {
            position: absolute;
            bottom: -40px;
            left: 20px; right: 20px;
            height: 40px;
            background: radial-gradient(ellipse, rgba(0,212,255,0.15), transparent 70%);
            filter: blur(15px);
            animation: shadow-pulse 6s ease-in-out infinite;
        }

        @keyframes shadow-pulse {
            0%, 100% { opacity: 0.6; transform: scaleX(0.9); }
            50%       { opacity: 1;   transform: scaleX(1.05); }
        }

        /* ═══════════════════════════════════
           SECTION FONCTIONNALITÉS
        ═══════════════════════════════════ */
        .features {
            position: relative;
            z-index: 1;
            padding: 100px 60px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-label {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: var(--accent);
            margin-bottom: 16px;
            text-align: center;
        }

        .section-title {
            font-family: var(--font-d);
            font-size: clamp(32px, 4vw, 52px);
            font-weight: 800;
            letter-spacing: -1px;
            color: #fff;
            text-align: center;
            line-height: 1.1;
            margin-bottom: 16px;
        }

        .section-desc {
            font-size: 17px;
            color: var(--muted);
            text-align: center;
            max-width: 560px;
            margin: 0 auto 60px;
            line-height: 1.7;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        @media (max-width: 900px)  { .features-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 600px)  { .features-grid { grid-template-columns: 1fr; } }

        .feature-card {
            background: rgba(11,17,32,0.8);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 20px;
            padding: 32px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--card-accent, var(--accent)), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .feature-card:hover {
            border-color: rgba(0,212,255,0.15);
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3), 0 0 0 1px rgba(0,212,255,0.05);
        }

        .feature-card:hover::before { opacity: 1; }

        .feature-icon {
            width: 52px; height: 52px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px;
            margin-bottom: 20px;
            background: var(--icon-bg, rgba(0,212,255,0.1));
            color: var(--card-accent, var(--accent));
        }

        .feature-title {
            font-family: var(--font-d);
            font-size: 17px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 10px;
        }

        .feature-desc {
            font-size: 14px;
            color: var(--muted);
            line-height: 1.7;
        }

        /* ═══════════════════════════════════
           SECTION COMMENT ÇA MARCHE
        ═══════════════════════════════════ */
        .how-it-works {
            position: relative;
            z-index: 1;
            padding: 100px 60px;
            background: rgba(11,17,32,0.5);
            border-top: 1px solid rgba(255,255,255,0.04);
            border-bottom: 1px solid rgba(255,255,255,0.04);
        }

        .steps-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 32px;
            max-width: 1100px;
            margin: 0 auto;
            position: relative;
        }

        .steps-grid::before {
            content: '';
            position: absolute;
            top: 30px; left: 10%; right: 10%;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--accent), transparent);
            opacity: 0.3;
        }

        @media (max-width: 900px) { .steps-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 600px) { .steps-grid { grid-template-columns: 1fr; } .steps-grid::before { display:none; } }

        .step {
            text-align: center;
            position: relative;
        }

        .step-number {
            width: 60px; height: 60px;
            border-radius: 50%;
            background: rgba(0,212,255,0.08);
            border: 1px solid rgba(0,212,255,0.2);
            display: flex; align-items: center; justify-content: center;
            font-family: var(--font-d);
            font-size: 20px;
            font-weight: 800;
            color: var(--accent);
            margin: 0 auto 20px;
        }

        .step-title {
            font-family: var(--font-d);
            font-size: 15px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 8px;
        }

        .step-desc {
            font-size: 13px;
            color: var(--muted);
            line-height: 1.6;
        }

        /* ═══════════════════════════════════
           SECTION CTA FINALE
        ═══════════════════════════════════ */
        .cta-section {
            position: relative;
            z-index: 1;
            padding: 120px 20px;
            text-align: center;
        }

        .cta-box {
            max-width: 700px;
            margin: 0 auto;
            background: rgba(11,17,32,0.8);
            border: 1px solid rgba(0,212,255,0.15);
            border-radius: 32px;
            padding: 60px 40px;
            backdrop-filter: blur(20px);
            position: relative;
            overflow: hidden;
        }

        .cta-box::before {
            content: '';
            position: absolute;
            top: -100px; left: 50%;
            transform: translateX(-50%);
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(0,212,255,0.08), transparent 70%);
            pointer-events: none;
        }

        .cta-title {
            font-family: var(--font-d);
            font-size: clamp(28px, 4vw, 44px);
            font-weight: 800;
            letter-spacing: -1px;
            color: #fff;
            margin-bottom: 16px;
        }

        .cta-desc {
            font-size: 16px;
            color: var(--muted);
            margin-bottom: 36px;
            line-height: 1.7;
        }

        /* ═══════════════════════════════════
           FOOTER
        ═══════════════════════════════════ */
        footer {
            position: relative;
            z-index: 1;
            padding: 32px 60px;
            border-top: 1px solid rgba(255,255,255,0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        footer p { font-size: 13px; color: var(--muted); }

        /* ═══════════════════════════════════
           ANIMATIONS GLOBALES
        ═══════════════════════════════════ */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        @media (max-width: 768px) {
            nav { padding: 16px 24px; }
            .nav-links { display: none; }
            .features, .how-it-works { padding: 60px 24px; }
            footer { flex-direction: column; gap: 12px; text-align: center; padding: 24px; }
        }
    </style>
</head>
<body>

{{-- Canvas fond animé --}}
<canvas id="canvas-bg"></canvas>

{{-- ══ NAVBAR ══ --}}
<nav>
    <a href="{{ route('home') }}" class="logo">
        <div class="logo-icon"><i class="ri-links-line"></i></div>
        <span class="logo-text">LinkPulse</span>
    </a>

    <ul class="nav-links">
        <li><a href="#fonctionnalites">Fonctionnalités</a></li>
        <li><a href="#comment">Comment ça marche</a></li>
        <li><a href="{{ route('visitor.home') }}">Espace visiteur</a></li>
    </ul>

    <div class="nav-cta">
        <a href="{{ route('login') }}" class="btn-nav-login">Connexion</a>
        <a href="{{ route('register') }}" class="btn-nav-register">
            <i class="ri-user-add-line"></i> S'inscrire
        </a>
    </div>
</nav>

{{-- ══ HERO ══ --}}
<section class="hero">
    <div class="hero-inner">
        <div class="hero-tag">
            <span class="hero-tag-dot"></span>
            La plateforme NFC nouvelle génération
        </div>

        <h1 class="hero-title">
            Votre identité numérique<br>
            <span class="gradient-text">au bout d'un tap</span>
        </h1>

        <p class="hero-subtitle">
            Créez, personnalisez et gérez vos portfolios NFC intelligents.
            Partagez vos coordonnées, projets et réseaux sociaux d'un simple geste.
        </p>

        <div class="hero-actions">
            <a href="{{ route('register') }}" class="btn-hero-primary">
                Commencer gratuitement <i class="ri-arrow-right-line"></i>
            </a>
            <a href="{{ route('login') }}" class="btn-hero-secondary">
                <i class="ri-login-box-line"></i> Se connecter
            </a>
        </div>
    </div>
</section>

{{-- ══ CARTE NFC 3D ══ --}}
<div class="hero-card-3d">
    <div class="nfc-card-scene">
        <div class="nfc-card" id="nfc-card">
            <div class="card-face card-front">
                <div class="card-glow"></div>
                <div class="card-nfc-symbol"><i class="ri-wifi-line"></i></div>

                {{-- Puce NFC dorée --}}
                <div class="card-chip"></div>

                {{-- Infos carte --}}
                <div>
                    <div class="card-name">JEAN DUPONT</div>
                    <div class="card-title-role">Développeur Full Stack</div>
                </div>
            </div>
        </div>
        <div class="nfc-card-shadow"></div>
    </div>
</div>

{{-- ══ FONCTIONNALITÉS ══ --}}
<section class="features" id="fonctionnalites">
    <p class="section-label reveal">Ce que vous pouvez faire</p>
    <h2 class="section-title reveal">Tout ce dont vous avez besoin</h2>
    <p class="section-desc reveal">
        LinkPulse centralise votre identité digitale et la connecte physiquement
        à travers la technologie NFC.
    </p>

    <div class="features-grid">
        <!-- Carte 1 -->
        <div class="feature-card reveal" style="--card-accent:#00d4ff;--icon-bg:rgba(0,212,255,0.1);">
            <div class="feature-icon"><i class="ri-folder-user-line"></i></div>
            <div class="feature-title">Portfolios multiples</div>
            <div class="feature-desc">Créez autant de portfolios que nécessaire, chacun avec son profil, ses projets, CV et réseaux sociaux.</div>
        </div>

        <!-- Carte 2 -->
        <div class="feature-card reveal" style="--card-accent:#7c3aed;--icon-bg:rgba(124,58,237,0.1);">
            <div class="feature-icon"><i class="ri-wifi-line"></i></div>
            <div class="feature-title">Cartes NFC intelligentes</div>
            <div class="feature-desc">Associez chaque portfolio à une carte NFC. Un tap suffit pour partager toutes vos informations.</div>
        </div>

        <!-- Carte 3 -->
        <div class="feature-card reveal" style="--card-accent:#10b981;--icon-bg:rgba(16,185,129,0.1);">
            <div class="feature-icon"><i class="ri-qr-code-line"></i></div>
            <div class="feature-title">QR Codes uniques</div>
            <div class="feature-desc">Générez un QR code par portfolio. Imprimez-le sur vos supports pour multiplier vos points de contact.</div>
        </div>

        <!-- Carte 4 -->
        <div class="feature-card reveal" style="--card-accent:#f59e0b;--icon-bg:rgba(245,158,11,0.1);">
            <div class="feature-icon"><i class="ri-bank-card-line"></i></div>
            <div class="feature-title">Modèles de carte</div>
            <div class="feature-desc">Concevez le design imprimé de votre carte NFC avec nos modèles professionnels personnalisables.</div>
        </div>

        <!-- Carte 5 -->
        <div class="feature-card reveal" style="--card-accent:#ef4444;--icon-bg:rgba(239,68,68,0.1);">
            <div class="feature-icon"><i class="ri-eye-line"></i></div>
            <div class="feature-title">Analytics en temps réel</div>
            <div class="feature-desc">Suivez qui a consulté vos portfolios, par quel canal (NFC, QR code, lien direct), quand et où.</div>
        </div>

        <!-- Carte 6 -->
        <div class="feature-card reveal" style="--card-accent:#00d4ff;--icon-bg:rgba(0,212,255,0.08);">
            <div class="feature-icon"><i class="ri-contacts-line"></i></div>
            <div class="feature-title">vCard & export</div>
            <div class="feature-desc">Vos visiteurs téléchargent votre contact en un clic. Exportez aussi vos prospects au format vCard.</div>
        </div>
    </div>
</section>

{{-- ══ COMMENT ÇA MARCHE ══ --}}
<section class="how-it-works" id="comment">
    <p class="section-label reveal">Démarrer en 4 étapes</p>
    <h2 class="section-title reveal" style="margin-bottom:50px;">Simple. Rapide. Puissant.</h2>

    <div class="steps-grid">
        <div class="step reveal">
            <div class="step-number">1</div>
            <div class="step-title">Créez votre compte</div>
            <div class="step-desc">Inscrivez-vous et attendez la validation rapide de l'administrateur.</div>
        </div>
        <div class="step reveal" style="transition-delay:0.1s;">
            <div class="step-number">2</div>
            <div class="step-title">Construisez votre portfolio</div>
            <div class="step-desc">Ajoutez photo, description, projets, CV et liens sociaux.</div>
        </div>
        <div class="step reveal" style="transition-delay:0.2s;">
            <div class="step-number">3</div>
            <div class="step-title">Associez votre carte NFC</div>
            <div class="step-desc">Scannez votre puce et liez-la à votre portfolio en quelques secondes.</div>
        </div>
        <div class="step reveal" style="transition-delay:0.3s;">
            <div class="step-number">4</div>
            <div class="step-title">Partagez d'un tap</div>
            <div class="step-desc">Approchez votre carte d'un smartphone : votre portfolio s'ouvre instantanément.</div>
        </div>
    </div>
</section>

{{-- ══ CTA FINALE ══ --}}
<section class="cta-section">
    <div class="cta-box reveal">
        <h2 class="cta-title">Prêt à passer au niveau supérieur ?</h2>
        <p class="cta-desc">
            Rejoignez LinkPulse et offrez à votre réseau une expérience de contact moderne et mémorable.
        </p>
        <div style="display:flex; justify-content:center; gap:14px; flex-wrap:wrap;">
            <a href="{{ route('register') }}" class="btn-hero-primary">
                Créer mon compte <i class="ri-arrow-right-line"></i>
            </a>
            <a href="{{ route('visitor.report') }}" class="btn-hero-secondary">
                <i class="ri-alarm-warning-line"></i> Signaler une fraude
            </a>
        </div>
    </div>
</section>

{{-- ══ FOOTER ══ --}}
<footer>
    <p>© {{ date('Y') }} LinkPulse. Tous droits réservés.</p>
    <p>Conçu pour les professionnels modernes.</p>
</footer>

<script>
// ═══════════════════════════════════════════════════════
// CANVAS — Particules flottantes en arrière-plan
// ═══════════════════════════════════════════════════════
const canvas  = document.getElementById('canvas-bg');
const ctx     = canvas.getContext('2d');
let   W, H, particles = [];

function resize() {
    W = canvas.width  = window.innerWidth;
    H = canvas.height = window.innerHeight;
}

class Particle {
    constructor() { this.reset(); }
    reset() {
        this.x     = Math.random() * W;
        this.y     = Math.random() * H;
        this.vx    = (Math.random() - 0.5) * 0.3;
        this.vy    = (Math.random() - 0.5) * 0.3;
        this.size  = Math.random() * 1.5 + 0.5;
        this.alpha = Math.random() * 0.4 + 0.1;
        this.color = Math.random() > 0.7 ? '#7c3aed' : '#00d4ff';
    }
    update() {
        this.x += this.vx;
        this.y += this.vy;
        if (this.x < 0 || this.x > W || this.y < 0 || this.y > H) this.reset();
    }
    draw() {
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
        ctx.fillStyle = this.color;
        ctx.globalAlpha = this.alpha;
        ctx.fill();
    }
}

function initParticles() {
    particles = [];
    for (let i = 0; i < 120; i++) particles.push(new Particle());
}

function drawConnections() {
    for (let i = 0; i < particles.length; i++) {
        for (let j = i + 1; j < particles.length; j++) {
            const dx = particles[i].x - particles[j].x;
            const dy = particles[i].y - particles[j].y;
            const d  = Math.sqrt(dx*dx + dy*dy);
            if (d < 100) {
                ctx.beginPath();
                ctx.moveTo(particles[i].x, particles[i].y);
                ctx.lineTo(particles[j].x, particles[j].y);
                ctx.strokeStyle = '#00d4ff';
                ctx.globalAlpha = (1 - d/100) * 0.08;
                ctx.lineWidth   = 0.5;
                ctx.stroke();
            }
        }
    }
}

function animate() {
    ctx.clearRect(0, 0, W, H);
    particles.forEach(p => { p.update(); p.draw(); });
    ctx.globalAlpha = 1;
    drawConnections();
    requestAnimationFrame(animate);
}

window.addEventListener('resize', () => { resize(); initParticles(); });
resize();
initParticles();
animate();

// ═══════════════════════════════════════════════════════
// CARTE NFC — Réactivité au mouvement de la souris
// ═══════════════════════════════════════════════════════
const card = document.getElementById('nfc-card');
if (card) {
    document.addEventListener('mousemove', (e) => {
        const cx = window.innerWidth  / 2;
        const cy = window.innerHeight / 2;
        const rx = (e.clientY - cy) / cy * 15;
        const ry = (cx - e.clientX) / cx * 20;
        card.style.transform = `rotateX(${rx}deg) rotateY(${ry}deg) translateY(-6px)`;
        card.style.transition = 'transform 0.1s ease';
    });

    document.addEventListener('mouseleave', () => {
        card.style.transform = '';
        card.style.transition = 'transform 0.5s ease';
    });
}

// ═══════════════════════════════════════════════════════
// SCROLL REVEAL — Animation au défilement
// ═══════════════════════════════════════════════════════
const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry, i) => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
        }
    });
}, { threshold: 0.15 });

document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
</script>
</body>
</html>
