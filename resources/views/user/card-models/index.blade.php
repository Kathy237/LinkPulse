@extends('layouts.app')

@section('title', 'Modèles de carte')
@section('page-title', 'Modèles de carte NFC')

@section('content')

{{-- ══ MES DESIGNS SAUVEGARDÉS ══ --}}
@if(!empty($myDesigns))
<div style="margin-bottom:36px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
        <h3 style="font-family:var(--font-display);font-size:17px;font-weight:700;color:var(--color-white);">
            Mes designs sauvegardés
        </h3>
    </div>
    <div class="grid-3">
        @foreach($myDesigns as $design)
        <div class="card" style="padding:0;overflow:hidden;">
            {{-- Prévisualisation du design --}}
            <div style="height:120px;background:linear-gradient(135deg,#0e1a2e,#1a2a4a);display:flex;align-items:center;justify-content:center;border-bottom:1px solid var(--color-border);">
                <i class="ri-bank-card-fill" style="font-size:48px;color:rgba(0,212,255,0.3);"></i>
            </div>
            <div style="padding:16px 20px;">
                <div style="font-size:14px;font-weight:600;color:var(--color-white);margin-bottom:4px;">
                    {{ $design['name'] ?? 'Design sans nom' }}
                </div>
                @if(!empty($design['width_mm']) && !empty($design['height_mm']))
                <div class="text-xs text-muted">
                    {{ $design['width_mm'] }}mm × {{ $design['height_mm'] }}mm
                </div>
                @endif
                <div style="display:flex;gap:8px;margin-top:12px;flex-wrap:wrap;">
                    <a href="{{ route('user.card-designs.export', $design['id']) }}"
                       class="btn btn-secondary btn-sm">
                        <i class="ri-download-line"></i> PDF
                    </a>
                    <button onclick="window.print()" class="btn btn-secondary btn-sm">
                        <i class="ri-printer-line"></i> Imprimer
                    </button>
                    <form method="POST" action="{{ route('user.card-designs.destroy', $design['id']) }}"
                          onsubmit="return confirm('Supprimer ce design ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm btn-icon">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- ══ MODÈLES PRÉDÉFINIS ══ --}}
<div>
    <div style="margin-bottom:20px;">
        <h3 style="font-family:var(--font-display);font-size:17px;font-weight:700;color:var(--color-white);margin-bottom:6px;">
            Choisir un modèle
        </h3>
        <p class="text-muted text-sm">Sélectionnez un modèle pour personnaliser votre carte NFC imprimable.</p>
    </div>

    @if(!empty($templates))
    <div class="grid-3">
        @foreach($templates as $tpl)
        <div class="card" style="padding:0;overflow:hidden;cursor:pointer;"
             onclick="window.location='{{ route('user.card-models.edit', $tpl['id']) }}'">
            {{-- Aperçu visuel du template --}}
            <div style="height:140px;position:relative;overflow:hidden;background:{{ $tpl['preview_bg'] ?? 'linear-gradient(135deg,#0e1a2e,#1a2a4a)' }};border-bottom:1px solid var(--color-border);">

                {{-- Simulation d'une carte --}}
                <div style="position:absolute;inset:16px;border-radius:10px;background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);display:flex;flex-direction:column;justify-content:space-between;padding:14px;">
                    <div style="width:30px;height:22px;background:linear-gradient(135deg,#d4af37,#c9a227);border-radius:4px;"></div>
                    <div>
                        <div style="height:8px;width:60%;background:rgba(255,255,255,0.15);border-radius:4px;margin-bottom:6px;"></div>
                        <div style="height:6px;width:40%;background:rgba(0,212,255,0.2);border-radius:4px;"></div>
                    </div>
                </div>

                {{-- Badge type --}}
                <span class="badge badge-info" style="position:absolute;top:10px;right:10px;font-size:10px;">
                    {{ $tpl['category'] ?? 'Standard' }}
                </span>
            </div>

            <div style="padding:16px 20px;">
                <div style="font-size:14px;font-weight:600;color:var(--color-white);margin-bottom:4px;">
                    {{ $tpl['name'] ?? 'Modèle' }}
                </div>
                <div class="text-xs text-muted" style="margin-bottom:14px;">
                    {{ $tpl['description'] ?? '' }}
                </div>
                <a href="{{ route('user.card-models.edit', $tpl['id']) }}" class="btn btn-primary btn-sm">
                    <i class="ri-edit-line"></i> Personnaliser
                </a>
            </div>
        </div>
        @endforeach
    </div>

    @else
    <div class="empty-state">
        <i class="ri-bank-card-line"></i>
        <p>Aucun modèle disponible pour le moment.</p>
    </div>
    @endif
</div>

@endsection
