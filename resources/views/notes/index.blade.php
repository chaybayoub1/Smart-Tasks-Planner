{{-- resources/views/notes/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Notes')
@section('page-title', 'Notes')

@section('content')

{{-- ── ADD NOTE FORM ──────────────────────────────────────── --}}
<div style="margin-bottom:1.25rem">
    <div class="st-card">
        <div class="st-card-header">
            <div class="st-card-title">
                <div class="icon icon-violet"><i class="bi bi-plus-circle-fill"></i></div>
                New Note
            </div>
            <button type="button" class="btn-st-ghost" data-bs-toggle="collapse" data-bs-target="#addNoteForm" style="font-size:.75rem;padding:.3rem .65rem">
                <i class="bi bi-chevron-down"></i>
            </button>
        </div>
        <div class="collapse" id="addNoteForm">
            <div style="padding:1.25rem">
                <form method="POST" action="{{ route('notes.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="st-label">Title *</label>
                            <input type="text" name="title" class="st-input" placeholder="Note title…" value="{{ old('title') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="st-label">Subject</label>
                            <select name="subject_id" class="st-select">
                                <option value="">— None —</option>
                                @foreach($subjects as $s)
                                    <option value="{{ $s->id }}" {{ old('subject_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer;padding-bottom:.3rem">
                                <input type="checkbox" name="is_pinned" value="1" {{ old('is_pinned') ? 'checked' : '' }}
                                       style="width:15px;height:15px;accent-color:var(--c-amber)">
                                <span style="font-size:.8rem;color:var(--c-muted2)">Pin note</span>
                            </label>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button class="btn-st-primary" style="width:100%;justify-content:center">Save Note</button>
                        </div>
                        <div class="col-12">
                            <label class="st-label">Content *</label>
                            <textarea name="content" class="st-textarea" rows="5" placeholder="Write your note here…" required>{{ old('content') }}</textarea>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ── SEARCH + FILTER BAR ──────────────────────────────── --}}
<form method="GET" style="margin-bottom:1.25rem">
    <div class="st-card" style="padding:.75rem 1rem">
        <div style="display:flex;gap:.5rem;align-items:center;flex-wrap:wrap">
            <div style="position:relative;flex:1;min-width:200px">
                <i class="bi bi-search" style="position:absolute;left:.75rem;top:50%;transform:translateY(-50%);color:var(--c-muted);font-size:.85rem;pointer-events:none"></i>
                <input type="text" name="q" class="st-input" style="padding-left:2.2rem" placeholder="Search title or content…" value="{{ request('q') }}">
            </div>
            <select name="subject_id" class="st-select" style="width:auto;min-width:140px" onchange="this.form.submit()">
                <option value="">All Subjects</option>
                @foreach($subjects as $s)
                    <option value="{{ $s->id }}" {{ request('subject_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                @endforeach
            </select>
            <button class="btn-st-primary" style="padding:.48rem .9rem">
                <i class="bi bi-search"></i> Search
            </button>
            @if(request()->hasAny(['q','subject_id']))
                <a href="{{ route('notes.index') }}" class="btn-st-ghost" style="font-size:.78rem;padding:.45rem .75rem">Clear</a>
            @endif
            <span style="margin-left:auto;font-size:.78rem;color:var(--c-muted)">{{ $notes->total() }} note{{ $notes->total() != 1 ? 's' : '' }}</span>
        </div>
    </div>
</form>

{{-- ── NOTE CARDS GRID ──────────────────────────────────── --}}
<div class="row g-3">
    @forelse($notes as $note)
    <div class="col-md-4 col-sm-6">
        <div class="st-card h-100" style="{{ $note->is_pinned ? 'border-color:rgba(245,158,11,.3)' : '' }};transition:transform .2s,box-shadow .2s">
            @if($note->is_pinned)
                <div style="height:2px;background:linear-gradient(90deg,var(--c-amber),transparent)"></div>
            @endif
            <div style="padding:1rem;display:flex;flex-direction:column;height:100%">

                {{-- Header --}}
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:.65rem">
                    <div style="flex:1;min-width:0;margin-right:.5rem">
                        <div style="font-weight:700;font-size:.9rem;color:var(--c-text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;margin-bottom:.25rem">
                            @if($note->is_pinned)
                                <i class="bi bi-pin-fill" style="color:var(--c-amber);font-size:.8rem;margin-right:.3rem"></i>
                            @endif
                            {{ $note->title }}
                        </div>
                        @if($note->subject)
                            <span class="tag" style="background:{{ $note->subject->color }}15;color:{{ $note->subject->color }};border-color:{{ $note->subject->color }}30;font-size:.67rem">
                                {{ $note->subject->name }}
                            </span>
                        @endif
                    </div>
                    <div class="dropdown" style="flex-shrink:0">
                        <button class="btn-st-ghost" style="padding:.3rem .5rem;font-size:.85rem" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" style="background:var(--c-surface2);border:1px solid var(--c-border2);border-radius:var(--radius-md);padding:.35rem;min-width:140px">
                            <li><a class="dropdown-item" href="{{ route('notes.show', $note) }}" style="font-size:.82rem;color:var(--c-text);padding:.4rem .65rem;border-radius:6px">
                                <i class="bi bi-eye me-2" style="color:var(--c-teal)"></i>View
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('notes.edit', $note) }}" style="font-size:.82rem;color:var(--c-text);padding:.4rem .65rem;border-radius:6px">
                                <i class="bi bi-pencil me-2" style="color:var(--c-violet)"></i>Edit
                            </a></li>
                            <li>
                                <form method="POST" action="{{ route('notes.pin', $note) }}">
                                    @csrf @method('PATCH')
                                    <button class="dropdown-item" style="font-size:.82rem;color:var(--c-text);padding:.4rem .65rem;border-radius:6px;width:100%;background:none;border:none;cursor:pointer;text-align:left">
                                        <i class="bi bi-pin{{ $note->is_pinned ? '-angle' : '' }} me-2" style="color:var(--c-amber)"></i>
                                        {{ $note->is_pinned ? 'Unpin' : 'Pin' }}
                                    </button>
                                </form>
                            </li>
                            <li><hr style="border-color:var(--c-border);margin:.25rem 0"></li>
                            <li>
                                <form method="POST" action="{{ route('notes.destroy', $note) }}" onsubmit="return confirm('Delete this note?')">
                                    @csrf @method('DELETE')
                                    <button class="dropdown-item" style="font-size:.82rem;color:var(--c-coral);padding:.4rem .65rem;border-radius:6px;width:100%;background:none;border:none;cursor:pointer;text-align:left">
                                        <i class="bi bi-trash me-2"></i>Delete
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Excerpt --}}
                <p style="color:var(--c-muted2);font-size:.82rem;line-height:1.65;flex:1;overflow:hidden;display:-webkit-box;-webkit-line-clamp:4;-webkit-box-orient:vertical;margin-bottom:.75rem">
                    {{ $note->excerpt(200) }}
                </p>

                {{-- Footer --}}
                <div style="display:flex;align-items:center;justify-content:space-between;padding-top:.65rem;border-top:1px solid var(--c-border);margin-top:auto">
                    <span style="font-size:.72rem;color:var(--c-muted)">
                        <i class="bi bi-clock me-1"></i>{{ $note->updated_at->diffForHumans() }}
                    </span>
                    <a href="{{ route('notes.show', $note) }}" class="btn-st-ghost" style="font-size:.75rem;padding:.3rem .65rem">
                        Read <i class="bi bi-arrow-right" style="margin-left:.2rem"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="st-card">
            <div class="empty-state">
                <div class="empty-state-icon"><i class="bi bi-journal-x"></i></div>
                <p>No notes yet.<br><a href="#addNoteForm" onclick="document.getElementById('addNoteForm').classList.add('show')">Capture your first idea!</a></p>
            </div>
        </div>
    </div>
    @endforelse
</div>

<div style="margin-top:1.5rem">{{ $notes->links() }}</div>
@endsection
