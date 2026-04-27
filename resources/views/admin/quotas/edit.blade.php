@extends('layouts.app')

@section('title', 'Forfait utilisateur')
@section('page-title', 'Attribuer un forfait')

@section('content')

<div style="max-width:600px;">

    {{-- Info utilisateur --}}
    @if($targetUser)
    <div class="card" style="padding:20px 24px;margin-bottom:24px;">
        <div style="display:flex;align-items:center;gap:14px;">
            <div style="width:44px;height:44px;border-radius:50%;background:linear-gradient(135deg,var(--color-accent),var(--color-accent2));display:flex;align-items:center;justify-content:center;font-family:var(--font-display);font-size:17px;font-weight:700;color:#fff;">
                {{ strtoupper(substr($targetUser['name'] ?? 'U', 0, 1)) }}
            </div>
            <div>
                <div style="font-weight:600;color:var(--color-white);">{{ $targetUser['name'] ?? '—' }}</div>
                <div class="text-muted text-sm">{{ $targetUser['email'] ?? '—' }}</div>
            </div>
            <span class="badge badge-success" style="margin-left:auto;">Approuvé</span>
        </div>
    </div>
    @endif

    {{-- Formulaire quota --}}
    <div class="card">
        <div class="card-header">
            <i class="ri-coupon-line" style="color:var(--color-accent);margin-right:8px;"></i>
            Configuration du forfait
        </div>
        <div class="card-body">
            <p class="text-muted text-sm" style="margin-bottom:24px;">
                Laissez un champ vide pour ne pas imposer de limite (accès illimité par défaut).
            </p>

            <form method="POST" action="{{ route('admin.quotas.update', $userId) }}">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="max_portfolios">
                        <i class="ri-folder-line" style="color:var(--color-accent);"></i>
                        Nombre max de portfolios
                    </label>
                    <input type="number" name="max_portfolios" id="max_portfolios"
                           class="form-control"
                           value="{{ $quota['max_portfolios'] ?? '' }}"
                           placeholder="Illimité (laisser vide)"
                           min="1">
                    <div class="text-muted text-xs" style="margin-top:4px;">
                        Nombre maximum de portfolios que l'utilisateur peut créer.
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="max_links_per_portfolio">
                        <i class="ri-links-line" style="color:var(--color-accent);"></i>
                        Liens max par portfolio
                    </label>
                    <input type="number" name="max_links_per_portfolio" id="max_links_per_portfolio"
                           class="form-control"
                           value="{{ $quota['max_links_per_portfolio'] ?? '' }}"
                           placeholder="Illimité (laisser vide)"
                           min="1">
                </div>

                <div class="form-group">
                    <label class="form-label" for="max_cards">
                        <i class="ri-bank-card-line" style="color:var(--color-accent);"></i>
                        Designs de carte max
                    </label>
                    <input type="number" name="max_cards" id="max_cards"
                           class="form-control"
                           value="{{ $quota['max_cards'] ?? '' }}"
                           placeholder="Illimité (laisser vide)"
                           min="1">
                    <div class="text-muted text-xs" style="margin-top:4px;">
                        Nombre de modèles de carte NFC que l'utilisateur peut sauvegarder.
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="valid_until">
                        <i class="ri-calendar-line" style="color:var(--color-accent);"></i>
                        Forfait valide jusqu'au
                    </label>
                    <input type="datetime-local" name="valid_until" id="valid_until"
                           class="form-control"
                           value="{{ isset($quota['valid_until']) ? \Carbon\Carbon::parse($quota['valid_until'])->format('Y-m-d\TH:i') : '' }}">
                    <div class="text-muted text-xs" style="margin-top:4px;">
                        Laissez vide pour un forfait sans expiration.
                    </div>
                </div>

                <hr class="divider">

                <div style="display:flex;gap:12px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-save-line"></i> Enregistrer le forfait
                    </button>
                    <a href="{{ route('admin.users', ['status' => 'active']) }}"
                       class="btn btn-ghost">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Info actuelle --}}
    @if($quota)
    <div class="card" style="margin-top:20px;padding:20px 24px;">
        <div style="font-size:13px;font-weight:600;color:var(--color-muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:12px;">
            Forfait actuel
        </div>
        <div style="display:flex;flex-direction:column;gap:8px;">
            <div style="display:flex;justify-content:space-between;font-size:14px;">
                <span class="text-muted">Portfolios max</span>
                <span>{{ $quota['max_portfolios'] ?? 'Illimité' }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:14px;">
                <span class="text-muted">Liens max / portfolio</span>
                <span>{{ $quota['max_links_per_portfolio'] ?? 'Illimité' }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:14px;">
                <span class="text-muted">Cartes max</span>
                <span>{{ $quota['max_cards'] ?? 'Illimité' }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:14px;">
                <span class="text-muted">Expire le</span>
                <span>{{ isset($quota['valid_until']) ? \Carbon\Carbon::parse($quota['valid_until'])->format('d/m/Y à H:i') : 'Pas d\'expiration' }}</span>
            </div>
        </div>
    </div>
    @endif
</div>

@endsection
