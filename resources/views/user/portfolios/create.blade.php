@extends('layouts.app')

@section('title', isset($portfolio) ? 'Modifier le portfolio' : 'Nouveau portfolio')
@section('page-title', isset($portfolio) ? 'Modifier le portfolio' : 'Nouveau portfolio')

@section('content')

<div style="max-width:800px;">

    {{-- En-tête --}}
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:32px;">
        <a href="{{ route('user.portfolios') }}" class="btn btn-ghost btn-sm">
            <i class="ri-arrow-left-line"></i> Retour
        </a>
        <h2 class="page-title" style="font-size:22px;">
            {{ isset($portfolio) ? 'Modifier "' . ($portfolio['title'] ?? '') . '"' : 'Créer un nouveau portfolio' }}
        </h2>
    </div>

    {{-- Formulaire principal --}}
    <form method="POST"
        action="{{ isset($portfolio) && isset($portfolio['id']) ? route('user.portfolios.update', $portfolio['id']) : route('user.portfolios.store') }}"
        enctype="multipart/form-data"
        id="portfolio-form">
        @csrf
        @if(isset($portfolio))
            @method('POST') {{-- PUT simulé via _method dans le contrôleur --}}
        @endif

        {{-- ═══ INFORMATIONS GÉNÉRALES ═══ --}}
        <div class="card" style="margin-bottom:24px;">
            <div class="card-header">
                <i class="ri-user-line" style="color:var(--color-accent);margin-right:8px;"></i>
                Informations générales
            </div>
            <div class="card-body">

                {{-- Titre du portfolio --}}
                <div class="form-group">
                    <label class="form-label" for="title">
                        Titre du portfolio <span style="color:var(--color-danger)">*</span>
                    </label>
                    <input type="text" name="title" id="title"
                           class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                           value="{{ old('title', $portfolio['title'] ?? '') }}"
                           placeholder="Ex: Portfolio Développeur Web"
                           required maxlength="150">
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Description --}}
                <div class="form-group">
                    <label class="form-label" for="description">Description / Bio</label>
                    <textarea name="description" id="description"
                              class="form-control"
                              placeholder="Décrivez-vous en quelques lignes..."
                              rows="4" maxlength="1000">{{ old('description', $portfolio['description'] ?? '') }}</textarea>
                </div>

                {{-- Photo de profil --}}
                <div class="form-group">
                    <label class="form-label">Photo de profil</label>
                    <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
                        @if(!empty($portfolio['photo_url']))
                        <img src="{{ $portfolio['photo_url'] }}" alt="Photo actuelle"
                             style="width:70px;height:70px;border-radius:12px;object-fit:cover;border:2px solid var(--color-border);">
                        @endif
                        <div style="flex:1;min-width:200px;">
                            <input type="file" name="photo" id="photo"
                                   class="form-control"
                                   accept="image/*"
                                   style="padding:8px;">
                            <div class="text-xs text-muted" style="margin-top:4px;">
                                JPG, PNG, WEBP — max 5 Mo. {{ isset($portfolio['photo_url']) ? 'Laissez vide pour conserver la photo actuelle.' : '' }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- URL Portfolio officiel --}}
                <div class="form-group">
                    <label class="form-label" for="portfolio_url">
                        <i class="ri-global-line" style="color:var(--color-muted);"></i>
                        URL de votre portfolio officiel
                    </label>
                    <input type="url" name="portfolio_url" id="portfolio_url"
                           class="form-control"
                           value="{{ old('portfolio_url', $portfolio['portfolio_url'] ?? '') }}"
                           placeholder="https://mon-portfolio.com">
                </div>

            </div>
        </div>

        {{-- ═══ CV ═══ --}}
        <div class="card" style="margin-bottom:24px;">
            <div class="card-header">
                <i class="ri-file-text-line" style="color:var(--color-accent);margin-right:8px;"></i>
                Curriculum Vitae (CV)
            </div>
            <div class="card-body">
                @if(!empty($portfolio['cv_url']))
                <div style="display:flex;align-items:center;gap:12px;background:var(--color-surface);padding:12px 16px;border-radius:10px;margin-bottom:16px;">
                    <i class="ri-file-pdf-line" style="font-size:24px;color:var(--color-danger);"></i>
                    <div style="flex:1;">
                        <div style="font-size:13px;font-weight:500;color:var(--color-white);">CV actuel</div>
                        <a href="{{ $portfolio['cv_url'] }}" target="_blank" class="text-xs" style="color:var(--color-accent);">
                            Visualiser <i class="ri-external-link-line"></i>
                        </a>
                    </div>
                </div>
                @endif

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label" for="cv">
                        {{ isset($portfolio['cv_url']) ? 'Remplacer le CV (PDF)' : 'Uploader votre CV (PDF)' }}
                    </label>
                    <input type="file" name="cv" id="cv"
                           class="form-control"
                           accept=".pdf"
                           style="padding:8px;">
                    <div class="text-xs text-muted" style="margin-top:4px;">
                        Format PDF uniquement — max 10 Mo.
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══ RÉSEAUX SOCIAUX (édition seulement) ═══ --}}
        @if(isset($portfolio))
        <div class="card" style="margin-bottom:24px;">
            <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
                <span>
                    <i class="ri-share-line" style="color:var(--color-accent);margin-right:8px;"></i>
                    Réseaux sociaux
                </span>
            </div>
            <div class="card-body">
                {{-- Liens existants --}}
                @if(!empty($portfolio['social_links']))
                <div style="display:flex;flex-direction:column;gap:8px;margin-bottom:16px;">
                    @foreach($portfolio['social_links'] as $link)
                    <div style="display:flex;align-items:center;gap:10px;background:var(--color-surface);padding:10px 14px;border-radius:8px;">
                        <i class="ri-{{ $link['platform'] ?? 'link' }}-fill" style="font-size:18px;color:var(--color-accent);"></i>
                        <a href="{{ $link['url'] }}" target="_blank" style="flex:1;font-size:13px;color:var(--color-text);text-decoration:none;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            {{ $link['url'] }}
                        </a>
                        <form method="POST" action="{{ route('user.social-links.destroy', $link['id']) }}">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm btn-icon">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
                @endif

                {{-- Ajouter un lien social --}}
                <form method="POST" action="{{ route('user.social-links.store', $portfolio['id']) }}">
                    @csrf
                    <div style="display:flex;gap:10px;flex-wrap:wrap;">
                        <select name="platform" class="form-control" style="flex:0 0 160px;padding-left:12px;">
                            <option value="linkedin">LinkedIn</option>
                            <option value="github">GitHub</option>
                            <option value="twitter">Twitter / X</option>
                            <option value="instagram">Instagram</option>
                            <option value="facebook">Facebook</option>
                            <option value="youtube">YouTube</option>
                            <option value="tiktok">TikTok</option>
                            <option value="link">Autre lien</option>
                        </select>
                        <input type="url" name="url" class="form-control" placeholder="https://..." style="flex:1;min-width:200px;" required>
                        <button type="submit" class="btn btn-secondary btn-sm">
                            <i class="ri-add-line"></i> Ajouter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ═══ LIENS PERSONNALISÉS ═══ --}}
        <div class="card" style="margin-bottom:24px;">
            <div class="card-header">
                <i class="ri-external-link-line" style="color:var(--color-accent);margin-right:8px;"></i>
                Autres liens (sites, outils…)
            </div>
            <div class="card-body">
                @if(!empty($portfolio['custom_links']))
                <div style="display:flex;flex-direction:column;gap:8px;margin-bottom:16px;">
                    @foreach($portfolio['custom_links'] as $cl)
                    <div style="display:flex;align-items:center;gap:10px;background:var(--color-surface);padding:10px 14px;border-radius:8px;">
                        <i class="ri-link-m" style="color:var(--color-muted);"></i>
                        <span style="flex:0 0 100px;font-size:12px;font-weight:600;color:var(--color-text);text-transform:uppercase;letter-spacing:0.5px;">{{ $cl['label'] }}</span>
                        <a href="{{ $cl['url'] }}" target="_blank" style="flex:1;font-size:13px;color:var(--color-muted);text-decoration:none;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            {{ $cl['url'] }}
                        </a>
                        <form method="POST" action="{{ route('user.custom-links.destroy', $cl['id']) }}">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm btn-icon">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
                @endif

                <form method="POST" action="{{ route('user.custom-links.store', $portfolio['id']) }}">
                    @csrf
                    <div style="display:flex;gap:10px;flex-wrap:wrap;">
                        <input type="text" name="label" class="form-control" placeholder="Libellé (ex: Mon blog)" style="flex:0 0 180px;" maxlength="100" required>
                        <input type="url" name="url" class="form-control" placeholder="https://..." style="flex:1;min-width:200px;" required>
                        <button type="submit" class="btn btn-secondary btn-sm">
                            <i class="ri-add-line"></i> Ajouter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ═══ PROJETS ═══ --}}
        <div class="card" style="margin-bottom:24px;">
            <div class="card-header">
                <i class="ri-code-s-slash-line" style="color:var(--color-accent);margin-right:8px;"></i>
                Projets
            </div>
            <div class="card-body">
                @if(!empty($portfolio['projects']))
                <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:16px;">
                    @foreach($portfolio['projects'] as $project)
                    <div style="background:var(--color-surface);border-radius:10px;padding:14px;">
                        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:6px;">
                            <div>
                                <div style="font-size:14px;font-weight:600;color:var(--color-white);">{{ $project['title'] }}</div>
                                @if(!empty($project['url']))
                                <a href="{{ $project['url'] }}" target="_blank" style="font-size:12px;color:var(--color-accent);">
                                    <i class="ri-external-link-line"></i> Voir le projet
                                </a>
                                @endif
                                @if(!empty($project['github_url']))
                                <a href="{{ $project['github_url'] }}" target="_blank" style="font-size:12px;color:var(--color-muted);margin-left:10px;">
                                    <i class="ri-github-line"></i> GitHub
                                </a>
                                @endif
                            </div>
                            <form method="POST" action="{{ route('user.projects.destroy', $project['id']) }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm btn-icon">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </form>
                        </div>
                        @if(!empty($project['description']))
                        <p style="font-size:13px;color:var(--color-muted);">{{ $project['description'] }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif

                {{-- Ajouter un projet --}}
                <form method="POST" action="{{ route('user.projects.store', $portfolio['id']) }}">
                    @csrf
                    <div class="grid-2" style="gap:12px;margin-bottom:12px;">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Titre du projet *</label>
                            <input type="text" name="title" class="form-control" placeholder="Mon Projet" maxlength="200" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">URL du projet</label>
                            <input type="url" name="url" class="form-control" placeholder="https://...">
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom:12px;">
                        <label class="form-label">URL GitHub</label>
                        <input type="url" name="github_url" class="form-control" placeholder="https://github.com/...">
                    </div>
                    <div class="form-group" style="margin-bottom:12px;">
                        <label class="form-label">Description courte</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Description du projet..." maxlength="500"></textarea>
                    </div>
                    <button type="submit" class="btn btn-secondary btn-sm">
                        <i class="ri-add-line"></i> Ajouter ce projet
                    </button>
                </form>
            </div>
        </div>
        @endif

        {{-- ═══ BOUTONS DE SAUVEGARDE ═══ --}}
        <div style="display:flex;gap:12px;">
            <button type="submit" class="btn btn-primary">
                <i class="ri-save-line"></i>
                {{ isset($portfolio) ? 'Enregistrer les modifications' : 'Créer le portfolio' }}
            </button>
            <a href="{{ route('user.portfolios') }}" class="btn btn-ghost">
                Annuler
            </a>
        </div>

    </form>
</div>

@endsection
