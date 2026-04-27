@extends('layouts.app')

@section('title', 'Alertes')
@section('page-title', 'Alertes & Signalements')

@section('content')

<div class="tabs">
    <a href="{{ route('admin.reports', ['status' => 'pending']) }}"
       class="tab-btn {{ $status === 'pending' ? 'active' : '' }}">
        <i class="ri-alarm-warning-line"></i> En attente
    </a>
    <a href="{{ route('admin.reports', ['status' => 'resolved']) }}"
       class="tab-btn {{ $status === 'resolved' ? 'active' : '' }}">
        <i class="ri-checkbox-circle-line"></i> Résolus
    </a>
</div>

@if(count($reports) > 0)
<div style="display:flex;flex-direction:column;gap:16px;">
    @foreach($reports as $report)
    <div class="card" style="padding:24px;">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:12px;margin-bottom:16px;">

            <div>
                <div style="font-family:var(--font-display);font-size:15px;font-weight:700;color:var(--color-white);margin-bottom:4px;">
                    Signalement #{{ $report['id'] }}
                </div>
                <div class="text-muted text-xs">
                    {{ isset($report['created_at']) ? \Carbon\Carbon::parse($report['created_at'])->format('d/m/Y à H:i') : '' }}
                </div>
            </div>

            @if($report['status'] === 'pending')
                <span class="badge badge-warning"><i class="ri-time-line"></i> En attente</span>
            @else
                <span class="badge badge-success"><i class="ri-checkbox-circle-line"></i> Résolu</span>
            @endif
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
            {{-- Signaleur --}}
            <div style="background:var(--color-surface);border-radius:10px;padding:14px;">
                <div class="text-xs text-muted" style="margin-bottom:6px;text-transform:uppercase;letter-spacing:1px;">Signalé par</div>
                <div style="font-size:14px;color:var(--color-text);">
                    {{ $report['reporter_name'] ?? 'Anonyme' }}
                </div>
                @if(!empty($report['reporter_email']))
                <div class="text-xs text-muted">{{ $report['reporter_email'] }}</div>
                @endif
            </div>

            {{-- Cible --}}
            <div style="background:var(--color-surface);border-radius:10px;padding:14px;">
                <div class="text-xs text-muted" style="margin-bottom:6px;text-transform:uppercase;letter-spacing:1px;">Utilisateur signalé</div>
                @if(!empty($report['reported_portfolio_url']))
                <div style="font-size:13px;">
                    <a href="{{ $report['reported_portfolio_url'] }}" target="_blank"
                       style="color:var(--color-accent);text-decoration:none;word-break:break-all;">
                        <i class="ri-external-link-line"></i> {{ $report['reported_portfolio_url'] }}
                    </a>
                </div>
                @endif
                @if(!empty($report['reported_user_id']))
                <div class="text-xs text-muted" style="margin-top:4px;">ID utilisateur : {{ $report['reported_user_id'] }}</div>
                @endif
            </div>
        </div>

        {{-- Message --}}
        <div style="background:rgba(239,68,68,0.05);border:1px solid rgba(239,68,68,0.15);border-radius:10px;padding:14px;margin-bottom:16px;">
            <div class="text-xs text-muted" style="margin-bottom:6px;text-transform:uppercase;letter-spacing:1px;">Description de la fraude</div>
            <p style="font-size:14px;line-height:1.7;color:var(--color-text);">{{ $report['message'] }}</p>
        </div>

        @if(!empty($report['admin_notes']))
        <div style="background:rgba(16,185,129,0.05);border:1px solid rgba(16,185,129,0.15);border-radius:10px;padding:14px;margin-bottom:16px;">
            <div class="text-xs" style="color:var(--color-success);margin-bottom:6px;text-transform:uppercase;letter-spacing:1px;">Note admin</div>
            <p style="font-size:14px;color:var(--color-text);">{{ $report['admin_notes'] }}</p>
        </div>
        @endif

        {{-- Actions (seulement si en attente) --}}
        @if($report['status'] === 'pending')
        <hr class="divider">
        <div style="display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end;">

            {{-- Résoudre --}}
            <form method="POST" action="{{ route('admin.reports.resolve', $report['id']) }}" style="flex:1;min-width:200px;">
                @csrf
                <div style="margin-bottom:10px;">
                    <input type="text" name="admin_notes" class="form-control"
                           placeholder="Note optionnelle (visible dans le signalement)">
                </div>
                <button type="submit" class="btn btn-success btn-sm">
                    <i class="ri-check-double-line"></i> Marquer comme résolu
                </button>
            </form>

            {{-- Bloquer l'utilisateur --}}
            @if(!empty($report['reported_user_id']))
            <form method="POST" action="{{ route('admin.users.block', $report['reported_user_id']) }}"
                  onsubmit="return confirm('Bloquer cet utilisateur pour 30 jours ?')">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm">
                    <i class="ri-forbid-line"></i> Bloquer l'utilisateur
                </button>
            </form>
            @endif
        </div>
        @endif
    </div>
    @endforeach
</div>

@else
<div class="empty-state">
    <i class="ri-shield-check-line"></i>
    <p>
        @if($status === 'pending') Aucun signalement en attente. Tout est calme !
        @else Aucun signalement résolu pour le moment.
        @endif
    </p>
</div>
@endif

@endsection
