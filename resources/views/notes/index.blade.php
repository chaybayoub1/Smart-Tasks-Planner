{{-- resources/views/notes/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Notes')
@section('page-title', 'Notes')

@push('styles')
<style>
    :root {
        --notes-indigo: #6366f1;
        --notes-violet: #8b5cf6;
        --notes-emerald: #10b981;
        --notes-amber: #f59e0b;
        --notes-rose: #f43f5e;
        --notes-text: #1f2937;
        --notes-muted: #6b7280;
        --notes-soft: #f6f7ff;
        --notes-border: #e6e8f2;
        --notes-shadow: 0 2px 12px rgba(31,41,55,.06);
        --notes-shadow-hover: 0 10px 26px rgba(99,102,241,.13);
    }

    .notes-shell {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .notes-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        background: #fff;
        border: 1px solid var(--notes-border);
        border-radius: 12px;
        box-shadow: var(--notes-shadow);
        padding: 18px 20px;
    }

    .notes-eyebrow {
        margin: 0 0 5px;
        color: var(--notes-indigo);
        font-size: .72rem;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .notes-title {
        margin: 0;
        color: var(--notes-text);
        font-size: 1.35rem;
        font-weight: 800;
        letter-spacing: 0;
    }

    .notes-subtitle {
        margin: 6px 0 0;
        color: var(--notes-muted);
        font-size: .88rem;
        line-height: 1.55;
    }

    .notes-count {
        flex-shrink: 0;
        min-width: 112px;
        padding: 10px 14px;
        border: 1px solid rgba(99,102,241,.15);
        border-radius: 10px;
        background: var(--notes-soft);
        text-align: center;
    }

    .notes-count strong {
        display: block;
        color: var(--notes-indigo);
        font-size: 1.35rem;
        line-height: 1;
    }

    .notes-count span {
        color: var(--notes-muted);
        font-size: .72rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .notes-panel {
        background: #fff;
        border: 1px solid var(--notes-border);
        border-radius: 12px;
        box-shadow: var(--notes-shadow);
        overflow: hidden;
    }

    .notes-panel-header {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 14px 18px;
        border-bottom: 1px solid var(--notes-border);
        font-weight: 800;
        color: var(--notes-text);
    }

    .notes-panel-header i {
        color: var(--notes-indigo);
        font-size: 1.05rem;
    }

    .notes-panel-header .btn {
        margin-left: auto;
    }

    .notes-form-body {
        padding: 18px;
    }

    .notes-filter {
        display: grid;
        grid-template-columns: minmax(220px, 1fr) minmax(170px, auto) auto auto;
        gap: 10px;
        align-items: center;
        padding: 14px;
    }

    .notes-search {
        position: relative;
    }

    .notes-search i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--notes-muted);
        pointer-events: none;
    }

    .notes-search .form-control {
        padding-left: 36px;
    }

    .notes-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 16px;
    }

    .note-card {
        position: relative;
        min-height: 238px;
        display: flex;
        flex-direction: column;
        background: #fff;
        border: 1px solid var(--notes-border);
        border-radius: 12px;
        box-shadow: var(--notes-shadow);
        overflow: hidden;
        transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
    }

    .note-card:hover {
        transform: translateY(-2px);
        border-color: rgba(99,102,241,.26);
        box-shadow: var(--notes-shadow-hover);
    }

    .note-card.is-pinned {
        border-color: rgba(245,158,11,.35);
    }

    .note-card.is-pinned::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--notes-amber), rgba(245,158,11,.12));
    }

    .note-card-body {
        display: flex;
        flex: 1;
        flex-direction: column;
        padding: 16px;
    }

    .note-card-top {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 12px;
    }

    .note-icon {
        width: 36px;
        height: 36px;
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: rgba(99,102,241,.1);
        color: var(--notes-indigo);
        font-size: 1rem;
    }

    .note-title-wrap {
        min-width: 0;
        flex: 1;
    }

    .note-title {
        margin: 0;
        color: var(--notes-text);
        font-size: .96rem;
        font-weight: 800;
        line-height: 1.35;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .note-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 7px;
    }

    .note-subject {
        display: inline-flex;
        align-items: center;
        max-width: 100%;
        gap: 6px;
        padding: 3px 8px;
        border-radius: 999px;
        border: 1px solid currentColor;
        font-size: .72rem;
        font-weight: 700;
        line-height: 1.2;
    }

    .note-pin {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        color: var(--notes-amber);
        font-size: .72rem;
        font-weight: 800;
    }

    .note-menu-btn {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
    }

    .note-excerpt {
        flex: 1;
        margin: 0 0 16px;
        color: #4b5563;
        font-size: .86rem;
        line-height: 1.65;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 5;
        -webkit-box-orient: vertical;
        white-space: pre-line;
    }

    .note-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding-top: 12px;
        border-top: 1px solid var(--notes-border);
        margin-top: auto;
    }

    .note-time {
        color: var(--notes-muted);
        font-size: .75rem;
        white-space: nowrap;
    }

    .notes-empty {
        min-height: 240px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        background: #fff;
        border: 1px dashed #cfd3e6;
        border-radius: 12px;
        padding: 28px;
    }

    .notes-empty-icon {
        width: 54px;
        height: 54px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        background: rgba(99,102,241,.1);
        color: var(--notes-indigo);
        font-size: 1.5rem;
        margin-bottom: 12px;
    }

    @media (max-width: 768px) {
        .notes-header {
            flex-direction: column;
        }

        .notes-count {
            width: 100%;
            text-align: left;
        }

        .notes-filter {
            grid-template-columns: 1fr;
        }

        .notes-filter .btn,
        .notes-filter .form-select {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="notes-shell">
    <div class="notes-header">
        <div>
            <p class="notes-eyebrow">Knowledge base</p>
            <h1 class="notes-title">Notes de cours</h1>
            <p class="notes-subtitle">Capture tes idees, classe-les par matiere et retrouve rapidement les informations importantes.</p>
        </div>
        <div class="notes-count">
            <strong>{{ $notes->total() }}</strong>
            <span>{{ $notes->total() > 1 ? 'notes' : 'note' }}</span>
        </div>
    </div>

    <div class="notes-panel">
        <div class="notes-panel-header">
            <i class="bi bi-plus-circle-fill"></i>
            <span>Nouvelle note</span>
            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#addNoteForm" aria-controls="addNoteForm" aria-expanded="{{ $errors->any() ? 'true' : 'false' }}">
                <i class="bi bi-chevron-down"></i>
            </button>
        </div>
        <div class="collapse {{ $errors->any() ? 'show' : '' }}" id="addNoteForm">
            <div class="notes-form-body">
                <form method="POST" action="{{ route('notes.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-lg-5">
                            <label class="form-label small fw-semibold">Titre *</label>
                            <input type="text" name="title" class="form-control" placeholder="Ex. Resume du chapitre 3" value="{{ old('title') }}" required>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label small fw-semibold">Matiere</label>
                            <select name="subject_id" class="form-select">
                                <option value="">Aucune matiere</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 d-flex align-items-end">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="is_pinned" value="1" id="isPinned" {{ old('is_pinned') ? 'checked' : '' }}>
                                <label class="form-check-label small fw-semibold" for="isPinned">Epingler</label>
                            </div>
                        </div>
                        <div class="col-lg-2 d-flex align-items-end">
                            <button class="btn btn-primary w-100">
                                <i class="bi bi-save me-1"></i> Enregistrer
                            </button>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Contenu *</label>
                            <textarea name="content" class="form-control" rows="5" placeholder="Ecris ta note ici..." required>{{ old('content') }}</textarea>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <form method="GET" class="notes-panel">
        <div class="notes-filter">
            <div class="notes-search">
                <i class="bi bi-search"></i>
                <input type="text" name="q" class="form-control" placeholder="Rechercher dans les titres ou contenus" value="{{ request('q') }}">
            </div>
            <select name="subject_id" class="form-select" onchange="this.form.submit()">
                <option value="">Toutes les matieres</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                        {{ $subject->name }}
                    </option>
                @endforeach
            </select>
            <button class="btn btn-primary">
                <i class="bi bi-search me-1"></i> Filtrer
            </button>
            @if(request()->hasAny(['q', 'subject_id']))
                <a href="{{ route('notes.index') }}" class="btn btn-outline-secondary">Effacer</a>
            @endif
        </div>
    </form>

    @if($notes->count())
        <div class="notes-grid">
            @foreach($notes as $note)
                <article class="note-card {{ $note->is_pinned ? 'is-pinned' : '' }}">
                    <div class="note-card-body">
                        <div class="note-card-top">
                            <div class="note-icon">
                                <i class="bi bi-journal-text"></i>
                            </div>
                            <div class="note-title-wrap">
                                <h2 class="note-title" title="{{ $note->title }}">{{ $note->title }}</h2>
                                <div class="note-meta">
                                    @if($note->subject)
                                        <span class="note-subject" style="color: {{ $note->subject->color }}; background: {{ $note->subject->color }}14;">
                                            {{ $note->subject->name }}
                                        </span>
                                    @endif
                                    @if($note->is_pinned)
                                        <span class="note-pin"><i class="bi bi-pin-fill"></i> Epinglee</span>
                                    @endif
                                </div>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light note-menu-btn" type="button" data-bs-toggle="dropdown" aria-label="Actions">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('notes.show', $note) }}">
                                            <i class="bi bi-eye me-2 text-info"></i>Voir
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('notes.edit', $note) }}">
                                            <i class="bi bi-pencil me-2 text-primary"></i>Modifier
                                        </a>
                                    </li>
                                    <li>
                                        <form method="POST" action="{{ route('notes.pin', $note) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button class="dropdown-item" type="submit">
                                                <i class="bi bi-pin-angle me-2 text-warning"></i>{{ $note->is_pinned ? 'Retirer epingle' : 'Epingler' }}
                                            </button>
                                        </form>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('notes.destroy', $note) }}" onsubmit="return confirm('Delete this note?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="dropdown-item text-danger" type="submit">
                                                <i class="bi bi-trash me-2"></i>Supprimer
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <p class="note-excerpt">{{ $note->excerpt(220) }}</p>

                        <div class="note-footer">
                            <span class="note-time">
                                <i class="bi bi-clock me-1"></i>{{ $note->updated_at->diffForHumans() }}
                            </span>
                            <a href="{{ route('notes.show', $note) }}" class="btn btn-sm btn-outline-primary">
                                Lire <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-3">
            {{ $notes->links() }}
        </div>
    @else
        <div class="notes-empty">
            <div>
                <div class="notes-empty-icon"><i class="bi bi-journal-plus"></i></div>
                <h2 class="h6 fw-bold mb-1">Aucune note trouvee</h2>
                <p class="text-muted small mb-3">Ajoute une note ou modifie tes filtres de recherche.</p>
                <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#addNoteForm">
                    <i class="bi bi-plus-circle me-1"></i> Ajouter une note
                </button>
            </div>
        </div>
    @endif
</div>
@endsection
