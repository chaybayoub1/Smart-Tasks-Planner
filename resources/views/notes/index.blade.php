{{-- resources/views/notes/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Notes')
@section('page-title', 'Notes')

@section('content')
<div class="row g-4">

    {{-- ── ADD NOTE FORM ──────────────────────────────────────── --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <i class="bi bi-plus-circle-fill text-primary me-2"></i>
                <span>New Note</span>
                <button class="btn btn-sm btn-light ms-auto" type="button"
                        data-bs-toggle="collapse" data-bs-target="#addNoteForm">
                    <i class="bi bi-chevron-down"></i>
                </button>
            </div>
            <div class="collapse" id="addNoteForm">
                <div class="card-body">
                    <form method="POST" action="{{ route('notes.store') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label class="form-label fw-500 small">Title *</label>
                                <input type="text" name="title" class="form-control"
                                       placeholder="Note title…" value="{{ old('title') }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-500 small">Subject</label>
                                <select name="subject_id" class="form-select">
                                    <option value="">— None —</option>
                                    @foreach($subjects as $s)
                                        <option value="{{ $s->id }}"
                                            {{ old('subject_id') == $s->id ? 'selected' : '' }}>
                                            {{ $s->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox"
                                           name="is_pinned" id="is_pinned" value="1"
                                           {{ old('is_pinned') ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="is_pinned">
                                        📌 Pin note
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button class="btn btn-primary w-100">Save Note</button>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-500 small">Content *</label>
                                <textarea name="content" class="form-control" rows="5"
                                          placeholder="Write your note here… (supports plain text)" required>{{ old('content') }}</textarea>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ── SEARCH + FILTER BAR ──────────────────────────────── --}}
    <div class="col-12">
        <form method="GET" class="card">
            <div class="card-body py-2">
                <div class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" name="q" class="form-control border-start-0 ps-0"
                                   placeholder="Search title or content…"
                                   value="{{ request('q') }}">
                        </div>
                    </div>
                    <div class="col-auto">
                        <select name="subject_id" class="form-select form-select-sm"
                                onchange="this.form.submit()">
                            <option value="">All Subjects</option>
                            @foreach($subjects as $s)
                                <option value="{{ $s->id }}"
                                    {{ request('subject_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-sm btn-primary">
                            <i class="bi bi-search me-1"></i>Search
                        </button>
                    </div>
                    @if(request()->hasAny(['q','subject_id']))
                        <div class="col-auto">
                            <a href="{{ route('notes.index') }}"
                               class="btn btn-sm btn-outline-secondary">Clear</a>
                        </div>
                    @endif
                    <div class="col-auto ms-auto">
                        <span class="text-muted small">
                            {{ $notes->total() }} note{{ $notes->total() != 1 ? 's' : '' }}
                        </span>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- ── NOTE CARDS GRID ──────────────────────────────────── --}}
    @forelse($notes as $note)
    <div class="col-md-4 col-sm-6">
        <div class="card h-100 {{ $note->is_pinned ? 'border-warning border-opacity-50' : '' }}"
             style="transition: box-shadow .2s">
            <div class="card-body d-flex flex-column">
                {{-- Header --}}
                <div class="d-flex align-items-start justify-content-between mb-2">
                    <div class="flex-grow-1 me-2">
                        <h6 class="fw-600 mb-1 text-truncate">
                            @if($note->is_pinned)
                                <span class="me-1">📌</span>
                            @endif
                            {{ $note->title }}
                        </h6>
                        @if($note->subject)
                            <span class="badge"
                                  style="background:{{ $note->subject->color }}20;
                                         color:{{ $note->subject->color }};
                                         font-size:.7rem">
                                {{ $note->subject->name }}
                            </span>
                        @endif
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light border-0 p-1"
                                data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li>
                                <a class="dropdown-item" href="{{ route('notes.show', $note) }}">
                                    <i class="bi bi-eye me-2"></i>View
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('notes.edit', $note) }}">
                                    <i class="bi bi-pencil me-2"></i>Edit
                                </a>
                            </li>
                            <li>
                                <form method="POST" action="{{ route('notes.pin', $note) }}">
                                    @csrf @method('PATCH')
                                    <button class="dropdown-item">
                                        <i class="bi bi-pin{{ $note->is_pinned ? '-angle' : '' }} me-2"></i>
                                        {{ $note->is_pinned ? 'Unpin' : 'Pin' }}
                                    </button>
                                </form>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('notes.destroy', $note) }}"
                                      onsubmit="return confirm('Delete this note?')">
                                    @csrf @method('DELETE')
                                    <button class="dropdown-item text-danger">
                                        <i class="bi bi-trash me-2"></i>Delete
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Excerpt --}}
                <p class="text-muted small flex-grow-1 mb-2"
                   style="line-height:1.6; overflow:hidden; display:-webkit-box;
                          -webkit-line-clamp:4; -webkit-box-orient:vertical">
                    {{ $note->excerpt(200) }}
                </p>

                {{-- Footer --}}
                <div class="d-flex align-items-center justify-content-between mt-auto pt-2
                            border-top border-opacity-25">
                    <small class="text-muted">
                        <i class="bi bi-clock me-1"></i>
                        {{ $note->updated_at->diffForHumans() }}
                    </small>
                    <a href="{{ route('notes.show', $note) }}"
                       class="btn btn-sm btn-outline-primary py-0">Read →</a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-journal-x fs-1 d-block mb-3 text-secondary"></i>
                <h5>No notes yet</h5>
                <p class="small">Click "New Note" above to capture your first idea.</p>
            </div>
        </div>
    </div>
    @endforelse
</div>

<div class="mt-4">{{ $notes->links() }}</div>
@endsection

@push('styles')
<style>
    .card:hover { box-shadow: 0 4px 20px rgba(0,0,0,.09) !important; }
</style>
@endpush
