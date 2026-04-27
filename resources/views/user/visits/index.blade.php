@extends('layouts.app')

@section('title', 'Visiteurs')
@section('page-title', 'Mes visiteurs')

@section('content')

@if(count($visits) > 0)

<p class="text-muted text-sm" style="margin-bottom:24px;">
    {{ count($visits) }} visite(s) enregistrée(s). Cliquez sur <i class="ri-edit-line"></i> pour enrichir les informations d'un visiteur.
</p>

<div style="display:flex;flex-direction:column;gap:16px;">
    @foreach($visits as $visit)
    <div class="card" style="padding:24px;" id="visit-{{ $visit['id'] }}">

        <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:12px;margin-bottom:16px;">

            {{-- Infos de la visite --}}
            <div style="display:flex;align-items:center;gap:14px;flex-wrap:wrap;">

                {{-- Canal --}}
                @if(($visit['source'] ?? '') === 'qr')
                <div style="background:rgba(0,212,255,0.08);border:1px solid rgba(0,212,255,0.2);border-radius:8px;padding:8px 14px;display:flex;align-items:center;gap:6px;font-size:13px;color:var(--color-accent);">
                    <i class="ri-qr-code-line"></i> QR Code
                </div>
                @elseif(($visit['source'] ?? '') === 'nfc')
                <div style="background:rgba(124,58,237,0.08);border:1px solid rgba(124,58,237,0.2);border-radius:8px;padding:8px 14px;display:flex;align-items:center;gap:6px;font-size:13px;color:var(--color-accent2);">
                    <i class="ri-wifi-line"></i> NFC
                </div>
                @else
                <div style="background:rgba(16,185,129,0.08);border:1px solid rgba(16,185,129,0.2);border-radius:8px;padding:8px 14px;display:flex;align-items:center;gap:6px;font-size:13px;color:var(--color-success);">
                    <i class="ri-links-line"></i> Lien direct
                </div>
                @endif

                {{-- Portfolio concerné --}}
                @if(!empty($visit['portfolio']['title']))
                <span style="font-size:13px;color:var(--color-muted);">
                    <i class="ri-folder-line"></i> {{ $visit['portfolio']['title'] }}
                </span>
                @endif

                {{-- Date --}}
                <span style="font-size:13px;color:var(--color-muted);">
                    <i class="ri-calendar-line"></i>
                    {{ isset($visit['visited_at']) ? \Carbon\Carbon::parse($visit['visited_at'])->format('d/m/Y à H:i') : '—' }}
                </span>

                {{-- Lieu --}}
                @if(!empty($visit['location']))
                <span style="font-size:13px;color:var(--color-muted);">
                    <i class="ri-map-pin-line"></i> {{ $visit['location'] }}
                </span>
                @endif
            </div>

            {{-- Bouton édition --}}
            <button type="button"
                    class="btn btn-secondary btn-sm"
                    onclick="toggleEditForm({{ $visit['id'] }})">
                <i class="ri-edit-line"></i> Enrichir
            </button>
        </div>

        {{-- Infos visiteur si déjà renseignées --}}
        @if(!empty($visit['visitor_name']) || !empty($visit['visitor_email']))
        <div style="display:flex;flex-wrap:wrap;gap:16px;background:var(--color-surface);border-radius:10px;padding:12px 16px;margin-bottom:12px;">
            @if(!empty($visit['visitor_name']))
            <span style="font-size:13px;color:var(--color-text);"><i class="ri-user-line" style="color:var(--color-accent);"></i> {{ $visit['visitor_name'] }}</span>
            @endif
            @if(!empty($visit['visitor_email']))
            <span style="font-size:13px;color:var(--color-text);"><i class="ri-mail-line" style="color:var(--color-accent);"></i> {{ $visit['visitor_email'] }}</span>
            @endif
            @if(!empty($visit['visitor_phone']))
            <span style="font-size:13px;color:var(--color-text);"><i class="ri-phone-line" style="color:var(--color-accent);"></i> {{ $visit['visitor_phone'] }}</span>
            @endif
            @if(!empty($visit['visitor_address']))
            <span style="font-size:13px;color:var(--color-text);"><i class="ri-map-pin-line" style="color:var(--color-accent);"></i> {{ $visit['visitor_address'] }}</span>
            @endif

            {{-- Liens réseaux sociaux du visiteur --}}
            @if(!empty($visit['visitor_social_links']))
            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                @foreach($visit['visitor_social_links'] as $sl)
                @if(is_array($sl) && isset($sl['url']))
    <a href="{{ $sl['url'] }}" target="_blank"
       style="color:var(--color-muted);font-size:18px;transition:color .2s;"
       title="{{ $sl['platform'] ?? '' }}">
        <i class="ri-{{ $sl['platform'] ?? 'link' }}-fill"></i>
    </a>
@endif
                @endforeach
            </div>
            @endif

            {{-- Exporter --}}
            <a href="{{ route('user.visits.export', $visit['id']) }}" class="btn btn-secondary btn-sm" style="margin-left:auto;">
                <i class="ri-download-2-line"></i> Exporter
            </a>
        </div>
        @endif

        {{-- Formulaire édition (masqué par défaut) --}}
        <div id="edit-form-{{ $visit['id'] }}" style="display:none;">
            <hr class="divider">
            <form method="POST" action="{{ route('user.visits.update', $visit['id']) }}">
                @csrf

                <div class="grid-2" style="gap:12px;margin-bottom:12px;">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Nom du visiteur</label>
                        <input type="text" name="visitor_name" class="form-control"
                               value="{{ $visit['visitor_name'] ?? '' }}" placeholder="Nom Prénom">
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Email</label>
                        <input type="email" name="visitor_email" class="form-control"
                               value="{{ $visit['visitor_email'] ?? '' }}" placeholder="email@exemple.com">
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Téléphone</label>
                        <input type="text" name="visitor_phone" class="form-control"
                               value="{{ $visit['visitor_phone'] ?? '' }}" placeholder="+237...">
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Adresse</label>
                        <input type="text" name="visitor_address" class="form-control"
                               value="{{ $visit['visitor_address'] ?? '' }}" placeholder="Ville, Pays">
                    </div>
                </div>

                {{-- Liens sociaux du visiteur --}}
                <div class="form-group" style="margin-bottom:12px;">
                    <label class="form-label">Ajouter un lien réseau social</label>
                    <div style="display:flex;gap:8px;flex-wrap:wrap;">
                        <select name="social_platform" class="form-control" style="flex:0 0 140px;padding-left:12px;">
                            <option value="linkedin">LinkedIn</option>
                            <option value="twitter">Twitter</option>
                            <option value="instagram">Instagram</option>
                            <option value="facebook">Facebook</option>
                            <option value="github">GitHub</option>
                        </select>
                        <input type="url" name="social_url" class="form-control"
                               style="flex:1;min-width:160px;" placeholder="https://...">
                    </div>
                    <div class="text-xs text-muted" style="margin-top:4px;">
                        Les liens seront sauvegardés avec les informations du visiteur.
                    </div>
                </div>

                <div style="display:flex;gap:10px;">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="ri-save-line"></i> Enregistrer
                    </button>
                    <button type="button" class="btn btn-ghost btn-sm"
                            onclick="toggleEditForm({{ $visit['id'] }})">
                        Annuler
                    </button>
                </div>
            </form>
        </div>

    </div>
    @endforeach
</div>

@else
<div class="empty-state">
    <i class="ri-user-search-line"></i>
    <p>Aucun visiteur enregistré pour le moment.</p>
    <p class="text-sm text-muted">Les visites apparaîtront ici dès que quelqu'un consultera l'un de vos portfolios.</p>
</div>
@endif

@push('scripts')
<script>
function toggleEditForm(visitId) {
    const form = document.getElementById('edit-form-' + visitId);
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}
</script>
@endpush

@endsection
