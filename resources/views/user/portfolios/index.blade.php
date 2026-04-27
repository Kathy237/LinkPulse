@extends('layouts.app')

@section('title', 'Mes portfolios')
@section('page-title', 'Mes portfolios')

@section('topbar-actions')
    <a href="{{ route('user.portfolios.create') }}" class="btn btn-primary btn-sm">
        <i class="ri-add-line"></i> Nouveau portfolio
    </a>
@endsection

@section('content')

@if(count($portfolios) > 0)
<div class="grid-3">
    @foreach($portfolios as $portfolio)
    <div class="card" style="padding:0;overflow:hidden;">

        {{-- Bandeau supérieur coloré --}}
        <div style="height:6px;background:linear-gradient(90deg,var(--color-accent),var(--color-accent2));"></div>

        <div style="padding:24px;">
            {{-- En-tête --}}
            <div style="display:flex;align-items:flex-start;gap:14px;margin-bottom:16px;">
                {{-- Photo de profil ou initiale --}}
                @if(!empty($portfolio['photo_url']))
                <img src="{{ $portfolio['photo_url'] }}" alt="Photo"
                     style="width:50px;height:50px;border-radius:12px;object-fit:cover;flex-shrink:0;">
                @else
                <div style="width:50px;height:50px;border-radius:12px;background:linear-gradient(135deg,var(--color-accent),var(--color-accent2));display:flex;align-items:center;justify-content:center;font-family:var(--font-display);font-size:20px;font-weight:700;color:#fff;flex-shrink:0;">
                    {{ strtoupper(substr($portfolio['title'] ?? 'P', 0, 1)) }}
                </div>
                @endif

                <div style="min-width:0;flex:1;">
                    <div style="font-family:var(--font-display);font-size:16px;font-weight:700;color:var(--color-white);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                        {{ $portfolio['title'] ?? 'Sans titre' }}
                    </div>
                    <div class="text-muted text-xs" style="margin-top:2px;">
                        /p/{{ $portfolio['slug'] ?? '—' }}
                    </div>
                </div>

                {{-- Badge NFC --}}
                @if(!empty($portfolio['nfc_uid']))
                    <span class="badge badge-success" title="NFC associé"><i class="ri-wifi-line"></i></span>
                @else
                    <span class="badge" style="background:rgba(100,116,139,0.15);color:var(--color-muted);" title="Pas de NFC">
                        <i class="ri-wifi-off-line"></i>
                    </span>
                @endif
            </div>

            {{-- Statistiques de vues --}}
            <div style="display:flex;gap:12px;margin-bottom:16px;">
                <div style="flex:1;background:var(--color-surface);border-radius:8px;padding:10px;text-align:center;">
                    <div style="font-family:var(--font-display);font-size:20px;font-weight:700;color:var(--color-white);">
                        {{ $portfolio['views_link'] ?? 0 }}
                    </div>
                    <div class="text-xs text-muted"><i class="ri-links-line"></i> Via lien</div>
                </div>
                <div style="flex:1;background:var(--color-surface);border-radius:8px;padding:10px;text-align:center;">
                    <div style="font-family:var(--font-display);font-size:20px;font-weight:700;color:var(--color-white);">
                        {{ $portfolio['views_qr'] ?? 0 }}
                    </div>
                    <div class="text-xs text-muted"><i class="ri-qr-code-line"></i> Via QR</div>
                </div>
            </div>

            <hr class="divider">

            {{-- Boutons d'action --}}
            <div style="display:flex;flex-wrap:wrap;gap:8px;">

                {{-- Visualiser --}}
                @if(!empty($portfolio['slug']))
                <a href="{{ route('public.portfolio', $portfolio['slug']) }}"
                   target="_blank"
                   class="btn btn-secondary btn-sm" title="Visualiser">
                    <i class="ri-eye-line"></i> Voir
                </a>
                @else
                    <span class="btn btn-secondary btn-sm disabled" title="Slug manquant">
                        <i class="ri-eye-off-line"></i> Voir
                    </span>
                @endif

                {{-- QR Code --}}
                <a href="{{ route('user.portfolios.qrcode', $portfolio['id']) }}"
                   class="btn btn-secondary btn-sm" title="QR Code">
                    <i class="ri-qr-code-line"></i> QR
                </a>

                {{-- Modifier --}}
                <a href="{{ route('user.portfolios.edit', $portfolio['id']) }}"
                   class="btn btn-secondary btn-sm" title="Modifier">
                    <i class="ri-edit-line"></i> Modifier
                </a>

                {{-- Supprimer --}}
                <button type="button"
                        class="btn btn-danger btn-sm btn-icon"
                        title="Supprimer"
                        onclick="confirmDelete({{ $portfolio['id'] }}, {{ !empty($portfolio['nfc_uid']) ? 'true' : 'false' }})">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </div>
        </div>
    </div>
    @endforeach
</div>

@else
<div class="empty-state">
    <i class="ri-folder-open-line"></i>
    <p>Vous n'avez pas encore créé de portfolio.</p>
    <a href="{{ route('user.portfolios.create') }}" class="btn btn-primary">
        <i class="ri-add-line"></i> Créer mon premier portfolio
    </a>
</div>
@endif

{{-- ══ MODAL CONFIRMATION SUPPRESSION ══ --}}
<div class="modal-overlay" id="delete-modal">
    <div class="modal">
        <div class="modal-title">
            <i class="ri-delete-bin-line" style="color:var(--color-danger);margin-right:8px;"></i>
            Supprimer le portfolio
        </div>
        <p style="font-size:14px;color:var(--color-muted);margin-bottom:20px;">
            Voulez-vous vraiment supprimer ce portfolio ? Cette action est irréversible.
        </p>

        {{-- Message NFC --}}
        <div id="nfc-warning" style="display:none;background:rgba(245,158,11,0.08);border:1px solid rgba(245,158,11,0.2);border-radius:10px;padding:14px;margin-bottom:20px;font-size:13px;color:var(--color-warning);">
            <i class="ri-wifi-line"></i>
            Ce portfolio est associé à une carte NFC. La dissociation sera effectuée automatiquement avant la suppression.
        </div>

        <div style="display:flex;gap:10px;">
            <form id="delete-form" method="POST" style="flex:1;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" style="width:100%;">
                    <i class="ri-delete-bin-line"></i> Supprimer
                </button>
            </form>
            <button type="button" class="btn btn-ghost" onclick="closeDeleteModal()">
                Annuler
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(portfolioId, hasNfc) {
    const modal   = document.getElementById('delete-modal');
    const form    = document.getElementById('delete-form');
    const warning = document.getElementById('nfc-warning');

    // Définit l'action du formulaire
    form.action = '/espace/portfolios/' + portfolioId;

    // Affiche l'avertissement NFC si nécessaire
    warning.style.display = hasNfc ? 'block' : 'none';

    modal.classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('delete-modal').classList.remove('active');
}

// Ferme la modal si on clique hors de la boîte
document.getElementById('delete-modal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});
</script>
@endpush

@endsection
