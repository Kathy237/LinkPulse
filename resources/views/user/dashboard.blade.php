@extends('layouts.app')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')

@section('topbar-actions')
    <a href="{{ route('user.portfolios.create') }}" class="btn btn-primary btn-sm">
        <i class="ri-add-line"></i> Nouveau portfolio
    </a>
@endsection

@section('content')

<div style="margin-bottom:32px;">
    <h2 class="page-title">Bonjour, {{ session('user.name', 'Utilisateur') }} 👋</h2>
    <p class="text-muted text-sm" style="margin-top:4px;">Voici un résumé de votre activité LinkPulse.</p>
</div>

{{-- ══ STATISTIQUES ══ --}}
<div class="grid-4" style="margin-bottom:40px;">

    <a href="{{ route('user.portfolios') }}" class="stat-card"
       style="--accent-color:#00d4ff;--icon-bg:rgba(0,212,255,0.1);">
        <div class="stat-icon"><i class="ri-folder-user-line"></i></div>
        <div>
            <div class="stat-value">{{ $stats['total_portfolios'] ?? 0 }}</div>
            <div class="stat-label">Portfolios créés</div>
        </div>
    </a>

    <a href="{{ route('user.portfolios') }}" class="stat-card"
       style="--accent-color:#7c3aed;--icon-bg:rgba(124,58,237,0.1);">
        <div class="stat-icon"><i class="ri-layout-grid-line"></i></div>
        <div>
            <div class="stat-value">{{ $stats['total_projects'] ?? 0 }}</div>
            <div class="stat-label">Projets enregistrés</div>
        </div>
    </a>

    <a href="{{ route('user.visits') }}" class="stat-card"
       style="--accent-color:#10b981;--icon-bg:rgba(16,185,129,0.1);">
        <div class="stat-icon"><i class="ri-links-line"></i></div>
        <div>
            <div class="stat-value">{{ $stats['monthly_link_visits'] ?? 0 }}</div>
            <div class="stat-label">Vues via lien (mois)</div>
        </div>
    </a>

    <a href="{{ route('user.visits') }}" class="stat-card"
       style="--accent-color:#f59e0b;--icon-bg:rgba(245,158,11,0.1);">
        <div class="stat-icon"><i class="ri-qr-code-line"></i></div>
        <div>
            <div class="stat-value">{{ $stats['monthly_qr_visits'] ?? 0 }}</div>
            <div class="stat-label">Vues via QR (mois)</div>
        </div>
    </a>
</div>

<div class="grid-2">

    {{-- ══ PORTFOLIOS SANS NFC ══ --}}
    <div class="card">
        <div class="card-header" style="padding-bottom:16px;">
            <i class="ri-wifi-off-line" style="color:var(--color-warning);margin-right:8px;"></i>
            Portfolios sans carte NFC
            <span class="badge badge-warning" style="margin-left:8px;">
                {{ $stats['portfolios_without_nfc'] ?? 0 }}
            </span>
        </div>
        <div class="card-body" style="padding-top:0;">
            @if(($stats['portfolios_without_nfc'] ?? 0) > 0)
            <p class="text-muted text-sm" style="margin-bottom:16px;">
                Ces portfolios ne sont pas encore associés à une carte NFC.
            </p>
            <a href="{{ route('user.nfc') }}" class="btn btn-secondary btn-sm">
                <i class="ri-wifi-line"></i> Gérer mes cartes NFC
            </a>
            @else
            <p class="text-muted text-sm">Tous vos portfolios sont associés à une carte NFC. Excellent !</p>
            @endif
        </div>
    </div>

    {{-- ══ NOTIFICATIONS ══ --}}
    <div class="card">
        <div class="card-header" style="padding-bottom:16px;">
            <i class="ri-notification-3-line" style="color:var(--color-accent);margin-right:8px;"></i>
            Notifications récentes
        </div>
        <div class="card-body" style="padding-top:0;">
            @if(!empty($notifications))
            <div style="display:flex;flex-direction:column;gap:8px;">
                @foreach(array_slice($notifications, 0, 5) as $notif)
                <div style="display:flex;align-items:flex-start;gap:10px;padding:10px;background:var(--color-surface);border-radius:8px;">
                    <i class="ri-information-line" style="color:var(--color-accent);flex-shrink:0;margin-top:2px;"></i>
                    <div>
                        <div style="font-size:13px;color:var(--color-text);">{{ $notif['message'] ?? '' }}</div>
                        <div class="text-xs text-muted" style="margin-top:2px;">
                            {{ isset($notif['created_at']) ? \Carbon\Carbon::parse($notif['created_at'])->diffForHumans() : '' }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div style="text-align:center;padding:20px 0;">
                <i class="ri-notification-off-line" style="font-size:32px;color:var(--color-border);display:block;margin-bottom:8px;"></i>
                <p class="text-muted text-sm">Aucune notification pour le moment.</p>
            </div>
            @endif
        </div>
    </div>

</div>

{{-- ══ RACCOURCIS RAPIDES ══ --}}
<div class="card" style="margin-top:24px;padding:24px;">
    <div style="font-family:var(--font-display);font-size:15px;font-weight:700;color:var(--color-white);margin-bottom:20px;">
        Accès rapide
    </div>
    <div style="display:flex;flex-wrap:wrap;gap:10px;">
        <a href="{{ route('user.portfolios.create') }}" class="btn btn-secondary">
            <i class="ri-add-circle-line"></i> Créer un portfolio
        </a>
        <a href="{{ route('user.nfc') }}" class="btn btn-secondary">
            <i class="ri-wifi-line"></i> Associer une carte NFC
        </a>
        <a href="{{ route('user.card-models') }}" class="btn btn-secondary">
            <i class="ri-bank-card-line"></i> Modèles de carte
        </a>
        <a href="{{ route('user.visits') }}" class="btn btn-secondary">
            <i class="ri-eye-line"></i> Voir mes visiteurs
        </a>
        <a href="{{ route('user.config') }}" class="btn btn-secondary">
            <i class="ri-user-settings-line"></i> Mon profil
        </a>
    </div>
</div>

@endsection
