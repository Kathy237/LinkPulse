<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LinkPulse') — {{ session('app_name', config('app.name')) }}</title>

    {{-- Google Fonts : Syne (display) + DM Sans (body) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap" rel="stylesheet">

    {{-- Remix Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">

    <style>
        /* ═══════════════════════════════════════════════════════════
           DESIGN SYSTEM LINKPULSE
           Palette : Bleu nuit profond + Cyan électrique + Blanc cassé
        ═══════════════════════════════════════════════════════════ */
        :root {
            --color-bg:       #080c14;
            --color-surface:  #0e1420;
            --color-card:     #131929;
            --color-border:   #1e2a40;
            --color-accent:   #00d4ff;
            --color-accent2:  #7c3aed;
            --color-success:  #10b981;
            --color-warning:  #f59e0b;
            --color-danger:   #ef4444;
            --color-text:     #e2e8f0;
            --color-muted:    #64748b;
            --color-white:    #ffffff;

            --font-display: 'Syne', sans-serif;
            --font-body:    'DM Sans', sans-serif;

            --radius-sm:  6px;
            --radius-md:  12px;
            --radius-lg:  20px;
            --radius-xl:  32px;

            --shadow-glow: 0 0 30px rgba(0,212,255,0.15);
            --shadow-card: 0 4px 24px rgba(0,0,0,0.4);
            --sidebar-w:   260px;
            --topbar-h:    64px;

            --transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; }

        body {
            font-family: var(--font-body);
            background: var(--color-bg);
            color: var(--color-text);
            font-size: 15px;
            line-height: 1.6;
            min-height: 100vh;
        }

        /* ── Layout app ────────────────────────────────────────────── */
        .app-layout {
            display: flex;
            min-height: 100vh;
        }

        /* ── Sidebar ───────────────────────────────────────────────── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--color-surface);
            border-right: 1px solid var(--color-border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            z-index: 100;
            transition: var(--transition);
        }

        .sidebar-logo {
            padding: 24px 24px 20px;
            border-bottom: 1px solid var(--color-border);
        }

        .sidebar-logo a {
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--color-accent), var(--color-accent2));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; color: #fff;
        }

        .logo-text {
            font-family: var(--font-display);
            font-size: 20px;
            font-weight: 800;
            color: var(--color-white);
            letter-spacing: -0.5px;
        }

        /* Badge rôle */
        .role-badge {
            display: inline-block;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 2px 8px;
            border-radius: 20px;
            margin-top: 2px;
        }

        .role-badge.admin { background: rgba(124,58,237,0.2); color: var(--color-accent2); border: 1px solid var(--color-accent2); }
        .role-badge.user  { background: rgba(0,212,255,0.1);  color: var(--color-accent);  border: 1px solid var(--color-accent); }

        /* Navigation */
        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
            overflow-y: auto;
        }

        .nav-section-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--color-muted);
            padding: 12px 12px 6px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: var(--radius-sm);
            text-decoration: none;
            color: var(--color-muted);
            font-size: 14px;
            font-weight: 400;
            transition: var(--transition);
            margin-bottom: 2px;
            position: relative;
        }

        .nav-item i { font-size: 18px; flex-shrink: 0; }

        .nav-item:hover {
            background: rgba(0,212,255,0.06);
            color: var(--color-text);
        }

        .nav-item.active {
            background: linear-gradient(90deg, rgba(0,212,255,0.12), rgba(0,212,255,0.04));
            color: var(--color-accent);
            font-weight: 500;
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0; top: 20%; bottom: 20%;
            width: 3px;
            background: var(--color-accent);
            border-radius: 0 3px 3px 0;
        }

        /* Profil en bas de sidebar */
        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid var(--color-border);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            border-radius: var(--radius-sm);
            background: var(--color-card);
        }

        .user-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--color-accent), var(--color-accent2));
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; font-weight: 700; color: #fff;
            flex-shrink: 0;
        }

        .user-name { font-size: 13px; font-weight: 500; color: var(--color-text); }
        .user-email { font-size: 11px; color: var(--color-muted); }

        /* ── Contenu principal ─────────────────────────────────────── */
        .main-content {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ── Topbar ─────────────────────────────────────────────────── */
        .topbar {
            height: var(--topbar-h);
            background: var(--color-surface);
            border-bottom: 1px solid var(--color-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-title {
            font-family: var(--font-display);
            font-size: 18px;
            font-weight: 700;
            color: var(--color-white);
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* ── Page body ──────────────────────────────────────────────── */
        .page-body {
            padding: 32px 28px;
            flex: 1;
        }

        /* ── Cards ──────────────────────────────────────────────────── */
        .card {
            background: var(--color-card);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-md);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-glow), var(--shadow-card);
        }

        .card-header {
            padding: 20px 24px 0;
            font-family: var(--font-display);
            font-size: 15px;
            font-weight: 700;
            color: var(--color-white);
        }

        .card-body { padding: 20px 24px; }

        /* ── Stats cards ────────────────────────────────────────────── */
        .stat-card {
            background: var(--color-card);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-md);
            padding: 24px;
            display: flex;
            align-items: flex-start;
            gap: 16px;
            text-decoration: none;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 2px;
            background: var(--accent-color, var(--color-accent));
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.3);
        }

        .stat-card:hover::after { opacity: 1; }

        .stat-icon {
            width: 48px; height: 48px;
            border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
            background: var(--icon-bg, rgba(0,212,255,0.1));
            color: var(--accent-color, var(--color-accent));
            flex-shrink: 0;
        }

        .stat-value {
            font-family: var(--font-display);
            font-size: 32px;
            font-weight: 800;
            color: var(--color-white);
            line-height: 1;
        }

        .stat-label {
            font-size: 13px;
            color: var(--color-muted);
            margin-top: 4px;
        }

        /* ── Boutons ────────────────────────────────────────────────── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: var(--radius-sm);
            font-family: var(--font-body);
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: var(--transition);
            white-space: nowrap;
        }

        .btn-primary {
            background: var(--color-accent);
            color: #080c14;
        }
        .btn-primary:hover { background: #00b8d9; box-shadow: 0 0 20px rgba(0,212,255,0.3); }

        .btn-secondary {
            background: var(--color-card);
            color: var(--color-text);
            border: 1px solid var(--color-border);
        }
        .btn-secondary:hover { background: var(--color-border); color: var(--color-white); }

        .btn-success { background: var(--color-success); color: #fff; }
        .btn-success:hover { background: #059669; }

        .btn-danger { background: var(--color-danger); color: #fff; }
        .btn-danger:hover { background: #dc2626; }

        .btn-warning { background: var(--color-warning); color: #080c14; }
        .btn-warning:hover { background: #d97706; }

        .btn-ghost {
            background: transparent;
            color: var(--color-muted);
            border: 1px solid var(--color-border);
        }
        .btn-ghost:hover { color: var(--color-text); border-color: var(--color-text); }

        .btn-sm { padding: 6px 14px; font-size: 13px; }
        .btn-icon {
            width: 36px; height: 36px;
            padding: 0;
            border-radius: var(--radius-sm);
            justify-content: center;
        }

        /* ── Formulaires ────────────────────────────────────────────── */
        .form-group { margin-bottom: 20px; }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--color-text);
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            background: var(--color-surface);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-sm);
            padding: 10px 14px;
            color: var(--color-text);
            font-family: var(--font-body);
            font-size: 14px;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--color-accent);
            box-shadow: 0 0 0 3px rgba(0,212,255,0.1);
        }

        .form-control::placeholder { color: var(--color-muted); }

        select.form-control option {
            background: var(--color-surface);
            color: var(--color-text);
        }

        textarea.form-control { resize: vertical; min-height: 100px; }

        .invalid-feedback {
            font-size: 12px;
            color: var(--color-danger);
            margin-top: 4px;
        }

        /* ── Alertes flash ──────────────────────────────────────────── */
        .alert {
            padding: 14px 20px;
            border-radius: var(--radius-sm);
            font-size: 14px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid;
        }

        .alert-success { background: rgba(16,185,129,0.1); border-color: var(--color-success); color: #6ee7b7; }
        .alert-danger  { background: rgba(239,68,68,0.1);  border-color: var(--color-danger);  color: #fca5a5; }
        .alert-warning { background: rgba(245,158,11,0.1); border-color: var(--color-warning); color: #fcd34d; }
        .alert-info    { background: rgba(0,212,255,0.1);  border-color: var(--color-accent);  color: var(--color-accent); }

        /* ── Badges ────────────────────────────────────────────────── */
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-success { background: rgba(16,185,129,0.15); color: var(--color-success); }
        .badge-danger  { background: rgba(239,68,68,0.15);  color: var(--color-danger); }
        .badge-warning { background: rgba(245,158,11,0.15); color: var(--color-warning); }
        .badge-info    { background: rgba(0,212,255,0.15);  color: var(--color-accent); }

        /* ── Tables ─────────────────────────────────────────────────── */
        .table-wrapper {
            overflow-x: auto;
            border-radius: var(--radius-md);
            border: 1px solid var(--color-border);
        }

        table { width: 100%; border-collapse: collapse; }

        th {
            background: var(--color-surface);
            padding: 12px 16px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--color-muted);
            border-bottom: 1px solid var(--color-border);
        }

        td {
            padding: 14px 16px;
            font-size: 14px;
            border-bottom: 1px solid rgba(30,42,64,0.5);
        }

        tr:last-child td { border-bottom: none; }
        tr:hover td { background: rgba(0,212,255,0.02); }

        /* ── Grilles ────────────────────────────────────────────────── */
        .grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }

        @media (max-width: 1200px) { .grid-4 { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 900px)  { .grid-3, .grid-4 { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 640px)  {
            .grid-2, .grid-3, .grid-4 { grid-template-columns: 1fr; }
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; }
            .sidebar.open { transform: translateX(0); }
        }

        /* ── Utilitaires ─────────────────────────────────────────────── */
        .flex       { display: flex; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-2      { gap: 8px; }
        .gap-3      { gap: 12px; }
        .gap-4      { gap: 16px; }
        .mb-1       { margin-bottom: 4px; }
        .mb-2       { margin-bottom: 8px; }
        .mb-3       { margin-bottom: 12px; }
        .mb-4       { margin-bottom: 16px; }
        .mb-6       { margin-bottom: 24px; }
        .mt-2       { margin-top: 8px; }
        .mt-4       { margin-top: 16px; }
        .text-muted { color: var(--color-muted); }
        .text-sm    { font-size: 13px; }
        .text-xs    { font-size: 11px; }
        .font-bold  { font-weight: 700; }
        .page-title {
            font-family: var(--font-display);
            font-size: 26px;
            font-weight: 800;
            color: var(--color-white);
            letter-spacing: -0.5px;
        }

        /* ── Scrollbar personnalisée ────────────────────────────────── */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: var(--color-bg); }
        ::-webkit-scrollbar-thumb { background: var(--color-border); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--color-muted); }

        /* ── Modal ──────────────────────────────────────────────────── */
        .modal-overlay {
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(4px);
            z-index: 1000;
            display: flex; align-items: center; justify-content: center;
            opacity: 0; pointer-events: none;
            transition: opacity 0.2s ease;
        }

        .modal-overlay.active { opacity: 1; pointer-events: all; }

        .modal {
            background: var(--color-card);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-lg);
            padding: 32px;
            max-width: 500px;
            width: 90%;
            transform: scale(0.95) translateY(10px);
            transition: transform 0.2s ease;
        }

        .modal-overlay.active .modal { transform: scale(1) translateY(0); }

        .modal-title {
            font-family: var(--font-display);
            font-size: 18px;
            font-weight: 700;
            color: var(--color-white);
            margin-bottom: 12px;
        }

        /* ── Tabs ───────────────────────────────────────────────────── */
        .tabs {
            display: flex;
            gap: 4px;
            background: var(--color-surface);
            padding: 4px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--color-border);
            margin-bottom: 24px;
            width: fit-content;
        }

        .tab-btn {
            padding: 8px 20px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            color: var(--color-muted);
            cursor: pointer;
            text-decoration: none;
            transition: var(--transition);
            border: none;
            background: none;
            font-family: var(--font-body);
        }

        .tab-btn.active, .tab-btn:hover {
            background: var(--color-card);
            color: var(--color-text);
        }

        .tab-btn.active { color: var(--color-accent); }

        /* ── Divider ─────────────────────────────────────────────────── */
        .divider {
            border: none;
            border-top: 1px solid var(--color-border);
            margin: 20px 0;
        }

        /* ── Empty state ─────────────────────────────────────────────── */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--color-muted);
        }

        .empty-state i { font-size: 48px; margin-bottom: 16px; display: block; }
        .empty-state p { font-size: 15px; margin-bottom: 20px; }
    </style>

    @stack('styles')
</head>
<body>
<div class="app-layout">

    {{-- ══ SIDEBAR ══ --}}
    <aside class="sidebar" id="sidebar">
        {{-- Logo --}}
        <div class="sidebar-logo">
            <a href="{{ route('home') }}">
                <div class="logo-icon"><i class="ri-links-line"></i></div>
                <div>
                    <div class="logo-text">{{ session('app_name', 'LinkPulse') }}</div>
                </div>
            </a>
            @if(session('user'))
                <div class="mt-2">
                    @if(session('user.role') === 'admin')
                        <span class="role-badge admin">Administrateur</span>
                    @else
                        <span class="role-badge user">Utilisateur</span>
                    @endif
                </div>
            @endif
        </div>

        {{-- Navigation --}}
        <nav class="sidebar-nav">
            @if(session('user.role') === 'admin')
                {{-- MENU ADMIN --}}
                <span class="nav-section-label">Administration</span>

                <a href="{{ route('admin.dashboard') }}"
                   class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="ri-dashboard-line"></i> Tableau de bord
                </a>
                <a href="{{ route('admin.users', ['status' => 'pending']) }}"
                   class="nav-item {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                    <i class="ri-team-line"></i> Utilisateurs
                </a>
                <a href="{{ route('admin.reports') }}"
                   class="nav-item {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                    <i class="ri-alarm-warning-line"></i> Alertes
                </a>
                <a href="{{ route('admin.config') }}"
                   class="nav-item {{ request()->routeIs('admin.config') ? 'active' : '' }}">
                    <i class="ri-settings-3-line"></i> Configuration
                </a>

            @else
                {{-- MENU UTILISATEUR --}}
                <span class="nav-section-label">Mon espace</span>

                <a href="{{ route('user.dashboard') }}"
                   class="nav-item {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                    <i class="ri-dashboard-line"></i> Tableau de bord
                </a>
                <a href="{{ route('user.portfolios') }}"
                   class="nav-item {{ request()->routeIs('user.portfolios*') ? 'active' : '' }}">
                    <i class="ri-folder-user-line"></i> Portfolios
                </a>
                <a href="{{ route('user.nfc') }}"
                   class="nav-item {{ request()->routeIs('user.nfc*') ? 'active' : '' }}">
                    <i class="ri-wifi-line"></i> Cartes NFC
                </a>
                <a href="{{ route('user.card-models') }}"
                   class="nav-item {{ request()->routeIs('user.card-models*') ? 'active' : '' }}">
                    <i class="ri-bank-card-line"></i> Modèles de carte
                </a>

                <span class="nav-section-label" style="margin-top:8px">Analytics</span>

                <a href="{{ route('user.visits') }}"
                   class="nav-item {{ request()->routeIs('user.visits') ? 'active' : '' }}">
                    <i class="ri-eye-line"></i> Visiteurs
                </a>
                <a href="{{ route('user.history') }}"
                   class="nav-item {{ request()->routeIs('user.history') ? 'active' : '' }}">
                    <i class="ri-history-line"></i> Historique
                </a>
                <a href="{{ route('user.config') }}"
                   class="nav-item {{ request()->routeIs('user.config') ? 'active' : '' }}">
                    <i class="ri-user-settings-line"></i> Configuration
                </a>
            @endif
        </nav>

        {{-- Pied de sidebar : infos utilisateur + déconnexion --}}
        @if(session('user'))
        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar">
                    {{ strtoupper(substr(session('user.name', 'U'), 0, 1)) }}
                </div>
                <div style="flex:1; min-width:0;">
                    <div class="user-name" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                        {{ session('user.name') }}
                    </div>
                    <div class="user-email" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                        {{ session('user.email') }}
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-icon btn-ghost" title="Déconnexion">
                        <i class="ri-logout-box-r-line"></i>
                    </button>
                </form>
            </div>
        </div>
        @endif
    </aside>

    {{-- ══ CONTENU PRINCIPAL ══ --}}
    <div class="main-content">

        {{-- Topbar --}}
        <header class="topbar">
            <div class="flex items-center gap-3">
                {{-- Burger mobile --}}
                <button class="btn btn-icon btn-ghost" id="sidebar-toggle" style="display:none;">
                    <i class="ri-menu-line"></i>
                </button>
                <h1 class="topbar-title">@yield('page-title', 'LinkPulse')</h1>
            </div>
            <div class="topbar-actions">
                @yield('topbar-actions')
            </div>
        </header>

        {{-- Flash messages --}}
        <div style="padding: 0 28px; margin-top: 16px;">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="ri-checkbox-circle-line"></i>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="ri-error-warning-line"></i>
                    {{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="ri-error-warning-line"></i>
                    <ul style="margin:0; padding-left: 16px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        {{-- Contenu de la page --}}
        <main class="page-body">
            @yield('content')
        </main>
    </div>
</div>

<script>
// ── Burger menu mobile ────────────────────────────────────────────
const toggle  = document.getElementById('sidebar-toggle');
const sidebar = document.getElementById('sidebar');

if (toggle) {
    toggle.addEventListener('click', () => sidebar.classList.toggle('open'));
}

// Affiche le burger sur mobile
if (window.innerWidth <= 640 && toggle) {
    toggle.style.display = 'flex';
}

// Auto-hide flash après 5 secondes
document.querySelectorAll('.alert').forEach(el => {
    setTimeout(() => {
        el.style.transition = 'opacity 0.5s ease';
        el.style.opacity    = '0';
        setTimeout(() => el.remove(), 500);
    }, 5000);
});
</script>

@stack('scripts')
</body>
</html>
