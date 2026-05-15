{{-- resources/views/flashcards/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Flashcards')
@section('page-title', 'Flashcards')

@section('content')
<div class="row g-4">

    {{-- ── HEADER ROW ────────────────────────────────────────── --}}
    <div class="col-12 d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h5 class="fw-700 mb-0">My Flashcards</h5>
            <small class="text-muted">{{ $flashcards->total() }} cards total</small>
        </div>
        <div class="d-flex gap-2">
            @if($dueCount > 0)
            <a href="{{ route('flashcards.review') }}"
               class="btn btn-success fw-600">
                <i class="bi bi-play-fill me-1"></i>
                Review {{ $dueCount }} Due Card{{ $dueCount > 1 ? 's' : '' }}
            </a>
            @else
            <a href="{{ route('flashcards.review') }}"
               class="btn btn-outline-secondary">
                <i class="bi bi-layers me-1"></i>Review All
            </a>
            @endif
            <button class="btn btn-primary" type="button"
                    data-bs-toggle="collapse" data-bs-target="#addCardForm">
                <i class="bi bi-plus-lg me-1"></i>Add Card
            </button>
        </div>
    </div>

    {{-- ── ADD CARD FORM ─────────────────────────────────────── --}}
    <div class="col-12">
        <div class="collapse" id="addCardForm">
            <div class="card border-primary border-opacity-25">
                <div class="card-header bg-transparent">
                    <i class="bi bi-plus-circle-fill text-primary me-2"></i>New Flashcard
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('flashcards.store') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label class="form-label fw-500 small">Question / Front *</label>
                                <textarea name="question" class="form-control" rows="3"
                                          placeholder="What is…?" required>{{ old('question') }}</textarea>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fw-500 small">Answer / Back *</label>
                                <textarea name="answer" class="form-control" rows="3"
                                          placeholder="The answer is…" required>{{ old('answer') }}</textarea>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-500 small">Subject</label>
                                <select name="subject_id" class="form-select mb-3">
                                    <option value="">— None —</option>
                                    @foreach($subjects as $s)
                                        <option value="{{ $s->id }}"
                                            {{ old('subject_id') == $s->id ? 'selected' : '' }}>
                                            {{ $s->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <button class="btn btn-primary w-100">
                                    <i class="bi bi-plus me-1"></i>Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ── FILTER BAR ────────────────────────────────────────── --}}
    <div class="col-12">
        <form method="GET" class="d-flex gap-2 align-items-center flex-wrap">
            <select name="subject_id" class="form-select form-select-sm" style="width:auto"
                    onchange="this.form.submit()">
                <option value="">All Subjects</option>
                @foreach($subjects as $s)
                    <option value="{{ $s->id }}"
                        {{ request('subject_id') == $s->id ? 'selected' : '' }}>
                        {{ $s->name }}
                    </option>
                @endforeach
            </select>
            @if(request('subject_id'))
                <a href="{{ route('flashcards.index') }}"
                   class="btn btn-sm btn-outline-secondary">Clear</a>
            @endif
        </form>
    </div>

    {{-- ── CARD GRID ─────────────────────────────────────────── --}}
    @forelse($flashcards as $card)
    <div class="col-md-4 col-sm-6">
        <div class="card h-100 position-relative">
            {{-- Difficulty indicator strip --}}
            <div style="height:4px;border-radius:8px 8px 0 0;
                        background:{{ $card->difficulty === 'easy' ? '#10b981' : ($card->difficulty === 'hard' ? '#ef4444' : '#f59e0b') }}">
            </div>
            <div class="card-body d-flex flex-column">
                {{-- Subject badge --}}
                @if($card->subject)
                <span class="badge mb-2 align-self-start"
                      style="background:{{ $card->subject->color }}20;
                             color:{{ $card->subject->color }};font-size:.7rem">
                    {{ $card->subject->name }}
                </span>
                @endif

                <div class="fw-500 small mb-2 text-truncate-3"
                     style="overflow:hidden;display:-webkit-box;
                            -webkit-line-clamp:2;-webkit-box-orient:vertical">
                    <span class="text-muted" style="font-size:.7rem">Q:</span>
                    {{ $card->question }}
                </div>
                <div class="text-muted small mb-3 text-truncate-3"
                     style="overflow:hidden;display:-webkit-box;
                            -webkit-line-clamp:2;-webkit-box-orient:vertical;
                            border-left:3px solid #e2e8f0;padding-left:.5rem">
                    <span style="font-size:.7rem">A:</span> {{ $card->answer }}
                </div>

                <div class="mt-auto d-flex align-items-center justify-content-between">
                    <div>
                        <span class="badge text-bg-{{ $card->difficultyColor() }} me-1">
                            {{ $card->difficulty }}
                        </span>
                        <span class="text-muted" style="font-size:.7rem">
                            <i class="bi bi-arrow-repeat"></i> {{ $card->review_count }}x
                        </span>
                    </div>
                    <div class="d-flex gap-1">
                        <a href="{{ route('flashcards.edit', $card) }}"
                           class="btn btn-sm btn-light py-0">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('flashcards.destroy', $card) }}"
                              onsubmit="return confirm('Delete this card?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-light py-0 text-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>

                @if($card->isDueForReview())
                    <div class="mt-2">
                        <span class="badge text-bg-warning w-100 py-1">
                            <i class="bi bi-alarm me-1"></i>Due for review
                        </span>
                    </div>
                @else
                    <div class="mt-2">
                        <span class="badge text-bg-light text-muted w-100 py-1" style="font-size:.65rem">
                            Next: {{ $card->next_review_at?->diffForHumans() ?? 'now' }}
                        </span>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-layers fs-1 d-block mb-3 text-secondary"></i>
                <h5>No flashcards yet</h5>
                <p class="small">Click "Add Card" to create your first flashcard.</p>
                <button class="btn btn-primary" data-bs-toggle="collapse"
                        data-bs-target="#addCardForm">
                    <i class="bi bi-plus me-1"></i>Add First Card
                </button>
            </div>
        </div>
    </div>
    @endforelse
</div>

<div class="mt-4">{{ $flashcards->links() }}</div>
@endsection
