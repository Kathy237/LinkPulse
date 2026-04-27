@extends('layouts.app')

@section('title', 'Éditeur de carte')
@section('page-title', 'Éditeur de carte NFC')

@push('styles')
<style>
    /* ── Canvas de prévisualisation carte ─── */
    .card-preview-scene {
        perspective: 1000px;
        display: flex;
        justify-content: center;
        margin-bottom: 24px;
    }

    .card-preview {
        width: 340px;
        height: 210px;
        border-radius: 16px;
        padding: 24px;
        position: relative;
        background: v-bind(previewBg);
        box-shadow: 0 20px 50px rgba(0,0,0,0.5);
        border: 1px solid rgba(255,255,255,0.1);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: transform 0.5s ease;
        transform: rotateX(8deg) rotateY(-5deg);
    }

    .card-preview:hover {
        transform: rotateX(4deg) rotateY(-2deg);
    }

    .card-chip-preview {
        width: 38px; height: 30px;
        background: linear-gradient(135deg, #d4af37, #c9a227);
        border-radius: 5px;
    }

    .card-name-preview {
        font-family: var(--font-display);
        font-size: 15px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .card-role-preview {
        font-size: 10px;
        letter-spacing: 2px;
        text-transform: uppercase;
        opacity: 0.7;
        margin-top: 2px;
    }

    .card-nfc-icon {
        position: absolute;
        top: 18px; right: 20px;
        font-size: 22px;
        opacity: 0.4;
    }

    /* Palette de couleurs */
    .color-palette {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .color-swatch {
        width: 32px; height: 32px;
        border-radius: 8px;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.2s;
    }

    .color-swatch.active,
    .color-swatch:hover {
        border-color: var(--color-accent);
        transform: scale(1.1);
    }

    /* Sélecteur dimension */
    .size-btn {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        border: 1px solid var(--color-border);
        background: var(--color-surface);
        color: var(--color-muted);
        cursor: pointer;
        transition: all 0.2s;
    }

    .size-btn.active {
        border-color: var(--color-accent);
        color: var(--color-accent);
        background: rgba(0,212,255,0.06);
    }
</style>
@endpush

@section('content')

<div class="grid-2" style="gap:32px;align-items:flex-start;">

    {{-- ══ PRÉVISUALISATION ══ --}}
    <div>
        <div style="position:sticky;top:80px;">
            <h3 style="font-family:var(--font-display);font-size:15px;font-weight:700;color:var(--color-white);margin-bottom:20px;text-align:center;">
                Prévisualisation en temps réel
            </h3>

            <div class="card-preview-scene">
                <div class="card-preview" id="card-preview"
                     style="background: {{ $template['default_bg'] ?? 'linear-gradient(135deg,#0e1a2e 0%,#1a2a4a 100%)' }}; color: {{ $template['default_text_color'] ?? '#ffffff' }};">

                    <i class="ri-wifi-line card-nfc-icon"></i>
                    <div class="card-chip-preview"></div>

                    <div>
                        <div class="card-name-preview" id="preview-name">VOTRE NOM</div>
                        <div class="card-role-preview" id="preview-role">Votre titre</div>
                    </div>
                </div>
            </div>

            {{-- Infos dimensionnelles --}}
            <div id="preview-size-info" style="text-align:center;font-size:12px;color:var(--color-muted);">
                85.6 × 54 mm — Format standard carte de crédit
            </div>
        </div>
    </div>

    {{-- ══ FORMULAIRE D'ÉDITION ══ --}}
    <div>
        <form method="POST" action="{{ route('user.card-designs.store') }}" id="card-editor-form">
            @csrf
            <input type="hidden" name="template_id" value="{{ $template['id'] }}">

            {{-- Nom du design --}}
            <div class="card" style="margin-bottom:20px;">
                <div class="card-header">
                    <i class="ri-text" style="color:var(--color-accent);margin-right:8px;"></i>
                    Nommer ce design
                </div>
                <div class="card-body">
                    <input type="text" name="name" class="form-control"
                           placeholder="Ex: Ma carte pro 2024"
                           value="{{ $user['name'] ?? '' }}" required>
                </div>
            </div>

            {{-- Informations personnelles --}}
            <div class="card" style="margin-bottom:20px;">
                <div class="card-header">
                    <i class="ri-user-line" style="color:var(--color-accent);margin-right:8px;"></i>
                    Informations sur la carte
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Nom complet</label>
                        <input type="text" name="custom_data[name]" id="input-name"
                               class="form-control" value="{{ $user['name'] ?? '' }}"
                               placeholder="Jean Dupont" maxlength="50"
                               oninput="updatePreview('preview-name', this.value.toUpperCase() || 'VOTRE NOM')">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Titre / Poste</label>
                        <input type="text" name="custom_data[role]" id="input-role"
                               class="form-control" placeholder="Développeur Full Stack"
                               oninput="updatePreview('preview-role', this.value || 'Votre titre')">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Téléphone</label>
                        <input type="text" name="custom_data[phone]" class="form-control"
                               value="{{ $user['phone'] ?? '' }}" placeholder="+237 6XX XXX XXX">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="custom_data[email]" class="form-control"
                               value="{{ $user['email'] ?? '' }}" placeholder="email@exemple.com">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Site web</label>
                        <input type="url" name="custom_data[website]" class="form-control"
                               placeholder="https://mon-site.com">
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Adresse</label>
                        <input type="text" name="custom_data[address]" class="form-control"
                               placeholder="Douala, Cameroun">
                    </div>
                </div>
            </div>

            {{-- Couleurs --}}
            <div class="card" style="margin-bottom:20px;">
                <div class="card-header">
                    <i class="ri-palette-line" style="color:var(--color-accent);margin-right:8px;"></i>
                    Couleur de fond
                </div>
                <div class="card-body">
                    <div class="color-palette">
                        @php
                        $palettes = [
                            ['label'=>'Bleu nuit',   'bg'=>'linear-gradient(135deg,#0e1a2e,#1a2a4a)', 'text'=>'#ffffff'],
                            ['label'=>'Cyan',        'bg'=>'linear-gradient(135deg,#003d5b,#006994)', 'text'=>'#00d4ff'],
                            ['label'=>'Violet',      'bg'=>'linear-gradient(135deg,#1e0a3c,#3b1a6e)', 'text'=>'#c4b5fd'],
                            ['label'=>'Ardoise',     'bg'=>'linear-gradient(135deg,#1e293b,#334155)', 'text'=>'#e2e8f0'],
                            ['label'=>'Noir élégant','bg'=>'linear-gradient(135deg,#0a0a0a,#1a1a1a)', 'text'=>'#ffffff'],
                            ['label'=>'Or',          'bg'=>'linear-gradient(135deg,#1c1400,#3d2e00)', 'text'=>'#d4af37'],
                            ['label'=>'Vert forêt',  'bg'=>'linear-gradient(135deg,#0a1f0e,#1a3a1e)', 'text'=>'#6ee7b7'],
                            ['label'=>'Bordeaux',    'bg'=>'linear-gradient(135deg,#1f0a0e,#3a1018)', 'text'=>'#fca5a5'],
                        ];
                        @endphp

                        @foreach($palettes as $i => $pal)
                        <div class="color-swatch {{ $i === 0 ? 'active' : '' }}"
                             style="background: {{ $pal['bg'] }};"
                             title="{{ $pal['label'] }}"
                             onclick="setCardBg('{{ addslashes($pal['bg']) }}','{{ $pal['text'] }}', this)"
                             data-bg="{{ $pal['bg'] }}"
                             data-text="{{ $pal['text'] }}">
                        </div>
                        @endforeach
                    </div>
                    <input type="hidden" name="custom_data[bg]" id="selected-bg"
                           value="{{ addslashes($palettes[0]['bg']) }}">
                    <input type="hidden" name="custom_data[text_color]" id="selected-text-color"
                           value="{{ $palettes[0]['text'] }}">
                </div>
            </div>

            {{-- Dimensions --}}
            <div class="card" style="margin-bottom:24px;">
                <div class="card-header">
                    <i class="ri-ruler-2-line" style="color:var(--color-accent);margin-right:8px;"></i>
                    Dimensions
                </div>
                <div class="card-body">
                    <div style="display:flex;gap:10px;flex-wrap:wrap;">
                        <button type="button" class="size-btn active"
                                onclick="setSize(85.6, 54, 'Format standard (85.6×54mm)', this)">
                            Standard CR80
                        </button>
                        <button type="button" class="size-btn"
                                onclick="setSize(90, 50, 'Carré (90×50mm)', this)">
                            Carré
                        </button>
                        <button type="button" class="size-btn"
                                onclick="setSize(100, 60, 'Grand (100×60mm)', this)">
                            Grand format
                        </button>
                    </div>
                    <input type="hidden" name="width_mm"  id="width-mm"  value="85.6">
                    <input type="hidden" name="height_mm" id="height-mm" value="54">
                </div>
            </div>

            {{-- Boutons --}}
            <div style="display:flex;gap:12px;flex-wrap:wrap;">
                <button type="submit" class="btn btn-primary">
                    <i class="ri-save-line"></i> Sauvegarder ce design
                </button>
                <button type="button" class="btn btn-secondary" onclick="window.print()">
                    <i class="ri-printer-line"></i> Imprimer maintenant
                </button>
                <a href="{{ route('user.card-models') }}" class="btn btn-ghost">
                    Annuler
                </a>
            </div>
        </form>
    </div>

</div>

@push('scripts')
<script>
// Met à jour un texte dans la prévisualisation
function updatePreview(elId, value) {
    const el = document.getElementById(elId);
    if (el) el.textContent = value || '';
}

// Applique une couleur de fond à la carte
function setCardBg(bg, textColor, swatchEl) {
    document.getElementById('card-preview').style.background = bg;
    document.getElementById('card-preview').style.color = textColor;
    document.getElementById('selected-bg').value = bg;
    document.getElementById('selected-text-color').value = textColor;

    // Retire active des autres
    document.querySelectorAll('.color-swatch').forEach(s => s.classList.remove('active'));
    swatchEl.classList.add('active');
}

// Applique une taille
function setSize(w, h, label, btn) {
    document.getElementById('width-mm').value  = w;
    document.getElementById('height-mm').value = h;
    document.getElementById('preview-size-info').textContent = label;

    document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
}
</script>
@endpush

@endsection
