@extends('layouts.app')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')

@section('content')
<div style="margin-bottom:32px;">
    <h2 class="page-title">Bonjour, {{ session('user.name', 'Administrateur') }} 👋</h2>
    <p class="text-muted text-sm" style="margin-top:4px;">Voici un aperçu de l'activité de LinkPulse.</p>
</div>

{{-- ══ CARTES STATISTIQUES (raccourcis) ══ --}}
<div class="grid-4" style="margin-bottom:40px;">

    {{-- En attente --}}
    <a href="{{ route('admin.users', ['status' => 'pending']) }}"
       class="stat-card"
       style="--accent-color:#f59e0b;--icon-bg:rgba(245,158,11,0.1);">
        <div class="stat-icon"><i class="ri-time-line"></i></div>
        <div>
            <div class="stat-value">{{ $stats['pending'] ?? 0 }}</div>
            <div class="stat-label">En attente</div>
        </div>
    </a>

    {{-- Approuvés --}}
    <a href="{{ route('admin.users', ['status' => 'active']) }}"
       class="stat-card"
       style="--accent-color:#10b981;--icon-bg:rgba(16,185,129,0.1);">
        <div class="stat-icon"><i class="ri-user-follow-line"></i></div>
        <div>
            <div class="stat-value">{{ $stats['active'] ?? 0 }}</div>
            <div class="stat-label">Approuvés</div>
        </div>
    </a>

    {{-- Rejetés / Bloqués --}}
    <a href="{{ route('admin.users', ['status' => 'rejected']) }}"
       class="stat-card"
       style="--accent-color:#ef4444;--icon-bg:rgba(239,68,68,0.1);">
        <div class="stat-icon"><i class="ri-user-unfollow-line"></i></div>
        <div>
            <div class="stat-value">{{ $stats['rejected'] ?? 0 }}</div>
            <div class="stat-label">Rejetés / Bloqués</div>
        </div>
    </a>

    {{-- Alertes --}}
    <a href="{{ route('admin.reports', ['status' => 'pending']) }}"
       class="stat-card"
       style="--accent-color:#00d4ff;--icon-bg:rgba(0,212,255,0.1);">
        <div class="stat-icon"><i class="ri-alarm-warning-line"></i></div>
        <div>
            <div class="stat-value">{{ $stats['reports'] ?? 0 }}</div>
            <div class="stat-label">Alertes en attente</div>
        </div>
    </a>
</div>

{{-- ══ ACTIONS RAPIDES ══ --}}
<div class="grid-2">
    <div class="card">
        <div class="card-header" style="padding-bottom:16px;">
            <i class="ri-team-line" style="color:var(--color-accent);margin-right:8px;"></i>
            Actions rapides
        </div>
        <div class="card-body" style="padding-top:0;">
            <div style="display:flex;flex-direction:column;gap:10px;">
                <a href="{{ route('admin.users', ['status' => 'pending']) }}"
                   class="btn btn-secondary"
                   style="justify-content:flex-start;">
                    <i class="ri-user-received-line"></i>
                    Gérer les demandes en attente
                    @if(($stats['pending'] ?? 0) > 0)
                        <span class="badge badge-warning" style="margin-left:auto;">{{ $stats['pending'] }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.reports') }}"
                   class="btn btn-secondary"
                   style="justify-content:flex-start;">
                    <i class="ri-alarm-warning-line"></i>
                    Voir les alertes
                    @if(($stats['reports'] ?? 0) > 0)
                        <span class="badge badge-danger" style="margin-left:auto;">{{ $stats['reports'] }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.config') }}"
                   class="btn btn-secondary"
                   style="justify-content:flex-start;">
                    <i class="ri-settings-3-line"></i>
                    Configuration de l'application
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header" style="padding-bottom:16px;">
            <i class="ri-information-line" style="color:var(--color-accent);margin-right:8px;"></i>
            À propos de la plateforme
        </div>
        <div class="card-body" style="padding-top:0;">
            <div style="display:flex;flex-direction:column;gap:12px;">
                <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid var(--color-border);">
                    <span class="text-muted text-sm">Nom de l'application</span>
                    <span style="font-weight:500;color:var(--color-white);">{{ session('app_name', config('app.name')) }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid var(--color-border);">
                    <span class="text-muted text-sm">Version</span>
                    <span class="badge badge-info">1.0.0</span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;">
                    <span class="text-muted text-sm">Connecté en tant que</span>
                    <span style="font-weight:500;color:var(--color-accent);">{{ session('user.email') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
