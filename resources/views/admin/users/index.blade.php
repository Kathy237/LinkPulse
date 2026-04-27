@extends('layouts.app')

@section('title', 'Utilisateurs')
@section('page-title', 'Gestion des utilisateurs')

@section('topbar-actions')
    <a href="{{ route('admin.users', ['status' => 'pending']) }}" class="btn btn-sm btn-ghost">
        <i class="ri-refresh-line"></i> Actualiser
    </a>
@endsection

@section('content')

{{-- ══ ONGLETS STATUT ══ --}}
<div class="tabs">
    <a href="{{ route('admin.users', ['status' => 'pending']) }}"
       class="tab-btn {{ $status === 'pending' ? 'active' : '' }}">
        <i class="ri-time-line"></i> En attente
    </a>
    <a href="{{ route('admin.users', ['status' => 'active']) }}"
       class="tab-btn {{ $status === 'active' ? 'active' : '' }}">
        <i class="ri-user-follow-line"></i> Approuvés
    </a>
    <a href="{{ route('admin.users', ['status' => 'rejected']) }}"
       class="tab-btn {{ $status === 'rejected' ? 'active' : '' }}">
        <i class="ri-user-unfollow-line"></i> Rejetés / Bloqués
    </a>
</div>

{{-- ══ GRILLE UTILISATEURS ══ --}}
@if(count($users) > 0)
<div class="grid-3">
    @foreach($users as $user)
    <div class="card" style="padding:24px;">
        {{-- En-tête --}}
        <div style="display:flex;align-items:center;gap:14px;margin-bottom:16px;">
            <div style="width:46px;height:46px;border-radius:50%;background:linear-gradient(135deg,var(--color-accent),var(--color-accent2));display:flex;align-items:center;justify-content:center;font-family:var(--font-display);font-size:18px;font-weight:700;color:#fff;flex-shrink:0;">
                {{ strtoupper(substr($user['name'] ?? 'U', 0, 1)) }}
            </div>
            <div style="min-width:0;">
                <div style="font-weight:600;color:var(--color-white);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                    {{ $user['name'] ?? '—' }}
                </div>
                <div class="text-muted text-xs" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                    {{ $user['email'] ?? '—' }}
                </div>
            </div>
        </div>

        {{-- Infos --}}
        <div style="display:flex;flex-direction:column;gap:6px;margin-bottom:18px;">
            @if(!empty($user['phone']))
            <div style="display:flex;align-items:center;gap:6px;font-size:13px;color:var(--color-muted);">
                <i class="ri-phone-line"></i> {{ $user['phone'] }}
            </div>
            @endif
            @if(!empty($user['location']))
            <div style="display:flex;align-items:center;gap:6px;font-size:13px;color:var(--color-muted);">
                <i class="ri-map-pin-line"></i> {{ $user['location'] }}
            </div>
            @endif
            <div style="display:flex;align-items:center;gap:6px;font-size:13px;color:var(--color-muted);">
                <i class="ri-calendar-line"></i>
                Inscrit le {{ isset($user['created_at']) ? \Carbon\Carbon::parse($user['created_at'])->format('d/m/Y') : '—' }}
            </div>
            @if($status === 'active' && isset($user['portfolio_count']))
            <div style="display:flex;align-items:center;gap:6px;font-size:13px;color:var(--color-muted);">
                <i class="ri-folder-line"></i> {{ $user['portfolio_count'] }} portfolio(s)
            </div>
            @endif
        </div>

        <hr class="divider">

        {{-- ACTIONS SELON STATUT (structure corrigée) --}}
        <div style="display:flex;flex-wrap:wrap;gap:8px;">
            @if($status === 'pending')
                @if(!empty($user['id']))
                    {{-- Approuver --}}
                    <form method="POST" action="{{ route('admin.users.validate', $user['id']) }}" style="flex:1;">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm" style="width:100%;">
                            <i class="ri-check-line"></i> Approuver
                        </button>
                    </form>
                    {{-- Rejeter --}}
                    <form method="POST" action="{{ route('admin.users.reject', $user['id']) }}" style="flex:1;">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm" style="width:100%;">
                            <i class="ri-close-line"></i> Rejeter
                        </button>
                    </form>
                @else
                    <div class="alert alert-warning">ID utilisateur manquant</div>
                @endif

            @elseif($status === 'active')
                @if(!empty($user['id']))
                    <a href="{{ route('admin.quotas.edit', $user['id']) }}" class="btn btn-secondary btn-sm">
                        <i class="ri-coupon-line"></i> Forfait
                    </a>
                    <form method="POST" action="{{ route('admin.users.block', $user['id']) }}"
                          onsubmit="return confirm('Bloquer cet utilisateur 30 jours ?')">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-sm">
                            <i class="ri-forbid-line"></i> Bloquer
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.users.destroy', $user['id']) }}"
                          onsubmit="return confirm('Supprimer définitivement cet utilisateur ? Cette action est irréversible.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm btn-icon" title="Supprimer">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </form>
                @else
                    <div class="alert alert-warning">ID utilisateur manquant</div>
                @endif

            @elseif($status === 'rejected')
                @if(!empty($user['id']))
                    @if(!empty($user['restorable_until']) && \Carbon\Carbon::parse($user['restorable_until'])->isFuture())
                    <form method="POST" action="{{ route('admin.users.restore', $user['id']) }}" style="flex:1;">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm" style="width:100%;">
                            <i class="ri-refresh-line"></i> Restaurer
                        </button>
                    </form>
                    @endif
                    <form method="POST" action="{{ route('admin.users.validate', $user['id']) }}" style="flex:1;">
                        @csrf
                        <button type="submit" class="btn btn-secondary btn-sm" style="width:100%;">
                            <i class="ri-user-follow-line"></i> Approuver
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.users.destroy', $user['id']) }}"
                          onsubmit="return confirm('Supprimer définitivement ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm btn-icon" title="Supprimer">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </form>
                @else
                    <div class="alert alert-warning">ID utilisateur manquant</div>
                @endif
            @endif
        </div>
    </div>
    @endforeach
</div>
@else
<div class="empty-state">
    <i class="ri-user-search-line"></i>
    <p>
        @if($status === 'pending')   Aucune demande en attente de validation.
        @elseif($status === 'active') Aucun utilisateur approuvé pour le moment.
        @else                         Aucun utilisateur rejeté ou bloqué.
        @endif
    </p>
</div>
@endif

@endsection