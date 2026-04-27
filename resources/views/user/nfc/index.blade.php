@extends('layouts.app')

@section('title', 'Cartes NFC')
@section('page-title', 'Cartes NFC')

@section('content')

<div class="grid-2" style="gap:24px;align-items:flex-start;">

    {{-- ══ COLONNE GAUCHE : Enregistrer une carte ══ --}}
    <div>
        <div class="card" style="margin-bottom:24px;">
            <div class="card-header">
                <i class="ri-wifi-line" style="color:var(--color-accent);margin-right:8px;"></i>
                Associer une nouvelle carte NFC
            </div>
            <div class="card-body">
                <p class="text-muted text-sm" style="margin-bottom:20px;">
                    Taguez votre puce NFC pour récupérer son UID, puis associez-la à l'un de vos portfolios.
                </p>

                <form method="POST" action="{{ route('user.nfc.store') }}">
                    @csrf

                    <div class="form-group">
                        <label class="form-label" for="uid">
                            UID de la carte NFC <span style="color:var(--color-danger)">*</span>
                        </label>
                        <div style="position:relative;">
                            <i class="ri-wifi-line" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--color-muted);font-size:16px;pointer-events:none;"></i>
                            <input type="text" name="uid" id="uid"
                                   class="form-control {{ $errors->has('uid') ? 'is-invalid' : '' }}"
                                   value="{{ old('uid') }}"
                                   style="padding-left:42px;"
                                   placeholder="Ex: 04:AB:CD:EF:12:34"
                                   required>
                        </div>
                        @error('uid')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="text-xs text-muted" style="margin-top:4px;">
                            L'UID est l'identifiant unique gravé sur votre puce NFC.
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="portfolio_id">
                            Portfolio à associer <span style="color:var(--color-danger)">*</span>
                        </label>
                        <select name="portfolio_id" id="portfolio_id"
                                class="form-control {{ $errors->has('portfolio_id') ? 'is-invalid' : '' }}"
                                style="padding-left:14px;" required>
                            <option value="">-- Choisir un portfolio --</option>
                            @foreach($portfoliosWithoutNfc as $p)
                            <option value="{{ $p['id'] ?? '' }}" {{ old('portfolio_id') == ($p['id'] ?? '') ? 'selected' : '' }}>
                            {{ $p['title'] ?? 'Sans titre' }}
                            </option>
                            @endforeach
                        </select>
                        @error('portfolio_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        @if(empty($portfoliosWithoutNfc))
                        <div style="margin-top:8px;padding:10px 14px;background:rgba(245,158,11,0.06);border:1px solid rgba(245,158,11,0.2);border-radius:8px;font-size:13px;color:var(--color-warning);">
                            <i class="ri-information-line"></i>
                            Tous vos portfolios sont déjà associés à une carte NFC.
                        </div>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary"
                            {{ empty($portfoliosWithoutNfc) ? 'disabled' : '' }}>
                        <i class="ri-link-m"></i> Associer la carte
                    </button>
                </form>
            </div>
        </div>

        {{-- Info technique NFC --}}
        <div style="background:rgba(0,212,255,0.04);border:1px solid rgba(0,212,255,0.1);border-radius:12px;padding:16px 20px;">
            <div style="font-size:13px;font-weight:600;color:var(--color-accent);margin-bottom:8px;">
                <i class="ri-information-line"></i> Comment obtenir l'UID de votre carte ?
            </div>
            <ul style="font-size:13px;color:var(--color-muted);padding-left:16px;line-height:1.8;">
                <li>Utilisez une application NFC (NFC Tools, NFC TagInfo) sur smartphone</li>
                <li>Approchez la carte — l'UID s'affiche automatiquement</li>
                <li>Copiez-collez l'UID dans le champ ci-dessus</li>
            </ul>
        </div>
    </div>

    {{-- ══ COLONNE DROITE : Cartes associées ══ --}}
    <div>
        <div class="card">
            <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
                <span>
                    <i class="ri-bank-card-line" style="color:var(--color-accent);margin-right:8px;"></i>
                    Mes cartes NFC associées
                </span>
                <span class="badge badge-info">{{ count($nfcCards) }}</span>
            </div>
            <div class="card-body">

                @if(count($nfcCards) > 0)
                <div style="display:flex;flex-direction:column;gap:12px;">
                    @foreach($nfcCards as $card)
                    <div style="background:var(--color-surface);border-radius:12px;padding:16px;border:1px solid var(--color-border);">

                        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:10px;margin-bottom:10px;">
                            <div>
                                <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                                    <i class="ri-wifi-fill" style="color:var(--color-accent);font-size:16px;"></i>
                                    <span style="font-family:monospace;font-size:13px;color:var(--color-text);font-weight:600;">
                                        {{ $card['uid'] }}
                                    </span>
                                </div>
                                @if(!empty($card['portfolio']))
                                <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--color-muted);">
                                    <i class="ri-folder-line"></i>
                                    {{ $card['portfolio']['title'] ?? 'Portfolio sans titre' }}
                                </div>
                                @endif
                            </div>
                            <span class="badge badge-success">Actif</span>
                        </div>

                        <div style="display:flex;gap:8px;flex-wrap:wrap;">
                            {{-- Voir le portfolio --}}
                            @if(!empty($card['portfolio']['slug']))
                            <a href="{{ route('public.portfolio', $card['portfolio']['slug']) }}"
                               target="_blank"
                               class="btn btn-secondary btn-sm">
                                <i class="ri-eye-line"></i> Voir
                            </a>
                            @endif

                            {{-- Dissocier --}}
                            <form method="POST"
                                  action="{{ route('user.nfc.dissociate', $card['id']) }}"
                                  onsubmit="return confirm('Dissocier cette carte NFC de son portfolio ?')">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-sm">
                                    <i class="ri-link-unlink-m"></i> Dissocier
                                </button>
                            </form>

                            {{-- Supprimer --}}
                            <form method="POST"
                                  action="{{ route('user.nfc.destroy', $card['id']) }}"
                                  onsubmit="return confirm('Supprimer définitivement cette carte NFC ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm btn-icon">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>

                @else
                <div class="empty-state" style="padding:30px 0;">
                    <i class="ri-wifi-off-line"></i>
                    <p>Aucune carte NFC enregistrée.</p>
                </div>
                @endif

            </div>
        </div>
    </div>

</div>

@endsection
