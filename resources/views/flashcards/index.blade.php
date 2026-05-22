{{-- resources/views/flashcards/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Flashcards')
@section('page-title', 'Flashcards')

@section('content')

{{-- ── HEADER ROW ─────────────────────────────────────────── --}}
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem;margin-bottom:1.5rem">
    <div>
        <div class="section-title" style="margin-bottom:.2rem">Flashcards</div>
        <p style="color:var(--c-muted);font-size:.82rem;margin:0">{{ $flashcards->total() }} cards total</p>
    </div>
    <div style="display:flex;gap:.5rem">
        @if($dueCount > 0)
        <a href="{{ route('flashcards.review') }}" class="btn-st-primary">
            <i class="bi bi-play-fill"></i> Review {{ $dueCount }} Due Card{{ $dueCount > 1 ? 's' : '' }}
        </a>
        @else
        <a href="{{ route('flashcards.review') }}" class="btn-st-ghost">
            <i class="bi bi-layers"></i> Review All
        </a>
        @endif
        <button class="btn-st-ghost" type="button" data-bs-toggle="collapse" data-bs-target="#addCardForm">
            <i class="bi bi-plus-lg"></i> Add Card
        </button>
    </div>
</div>

{{-- ── ADD CARD FORM ─────────────────────────────────────── --}}
<div class="collapse" id="addCardForm" style="margin-bottom:1.25rem">
    <div class="st-card" style="border-color:rgba(0,212,170,.25)">
        <div class="st-card-header">
            <div class="st-card-title">
                <div class="icon icon-teal"><i class="bi bi-plus-circle-fill"></i></div>
                New Flashcard
            </div>
        </div>
        <div style="padding:1.25rem">
            <form method="POST" action="{{ route('flashcards.store') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="st-label">Question / Front *</label>
                        <textarea name="question" class="st-textarea" rows="3" placeholder="What is…?" required>{{ old('question') }}</textarea>
                    </div>
                    <div class="col-md-5">
                        <label class="st-label">Answer / Back *</label>
                        <textarea name="answer" class="st-textarea" rows="3" placeholder="The answer is…" required>{{ old('answer') }}</textarea>
                    </div>
                    <div class="col-md-2">
                        <label class="st-label">Subject</label>
                        <select name="subject_id" class="st-select" style="margin-bottom:.75rem">
                            <option value="">— None —</option>
                            @foreach($subjects as $s)
                                <option value="{{ $s->id }}" {{ old('subject_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                            @endforeach
                        </select>
                        <button class="btn-st-primary" style="width:100%;justify-content:center">
                            <i class="bi bi-plus"></i> Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ── FILTER BAR ────────────────────────────────────────── --}}
<form method="GET" style="margin-bottom:1.25rem;display:flex;gap:.5rem;align-items:center;flex-wrap:wrap">
    <select name="subject_id" class="st-select" style="width:auto;min-width:140px" onchange="this.form.submit()">
        <option value="">All Subjects</option>
        @foreach($subjects as $s)
            <option value="{{ $s->id }}" {{ request('subject_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
        @endforeach
    </select>
    @if(request('subject_id'))
        <a href="{{ route('flashcards.index') }}" class="btn-st-ghost" style="font-size:.78rem;padding:.45rem .75rem">Clear</a>
    @endif
</form>

{{-- ── CARD GRID ─────────────────────────────────────────── --}}
<div class="row g-3">
    @forelse($flashcards as $card)
    <div class="col-md-4 col-sm-6">
        <div class="st-card h-100" style="position:relative;overflow:hidden">
            {{-- Difficulty top line --}}
            <div style="height:3px;background:{{ $card->difficulty === 'easy' ? 'var(--c-teal)' : ($card->difficulty === 'hard' ? 'var(--c-coral)' : 'var(--c-amber)') }}"></div>

            <div style="padding:1rem;display:flex;flex-direction:column;height:calc(100% - 3px)">
                {{-- Subject badge --}}
                @if($card->subject)
                <span class="tag" style="background:{{ $card->subject->color }}15;color:{{ $card->subject->color }};border-color:{{ $card->subject->color }}30;width:fit-content;margin-bottom:.65rem;font-size:.68rem">
                    {{ $card->subject->name }}
                </span>
                @endif

                {{-- Question --}}
                <div style="font-size:.8rem;color:var(--c-muted);margin-bottom:.25rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em">Q</div>
                <div style="font-size:.875rem;font-weight:500;color:var(--c-text);flex:1;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;margin-bottom:.65rem">
                    {{ $card->question }}
                </div>

                {{-- Answer preview --}}
                <div style="font-size:.8rem;color:var(--c-muted);margin-bottom:.25rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em">A</div>
                <div style="font-size:.82rem;color:var(--c-muted2);overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;border-left:2px solid var(--c-border2);padding-left:.6rem;margin-bottom:.85rem">
                    {{ $card->answer }}
                </div>

                <div style="display:flex;align-items:center;justify-content:space-between;margin-top:auto">
                    <div style="display:flex;align-items:center;gap:.4rem">
                        @php $dc = ['easy'=>'teal','medium'=>'amber','hard'=>'coral'][$card->difficulty] ?? 'muted'; @endphp
                        <span class="tag tag-{{ $dc }}" style="font-size:.67rem">{{ $card->difficulty }}</span>
                        <span style="font-size:.72rem;color:var(--c-muted)">
                            <i class="bi bi-arrow-repeat"></i> {{ $card->review_count }}x
                        </span>
                    </div>
                    <div style="display:flex;gap:.3rem">
                        <a href="{{ route('flashcards.edit', $card) }}" class="btn-st-ghost" style="padding:.3rem .55rem;font-size:.8rem">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('flashcards.destroy', $card) }}" onsubmit="return confirm('Delete this card?')">
                            @csrf @method('DELETE')
                            <button class="btn-st-danger" style="padding:.3rem .55rem;font-size:.8rem">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>

                @if($card->isDueForReview())
                <div style="margin-top:.65rem;padding:.35rem .7rem;background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.2);border-radius:6px;font-size:.72rem;color:var(--c-amber);font-weight:600;text-align:center">
                    <i class="bi bi-alarm me-1"></i>Due for review
                </div>
                @else
                <div style="margin-top:.65rem;padding:.3rem;text-align:center;font-size:.68rem;color:var(--c-muted)">
                    Next: {{ $card->next_review_at?->diffForHumans() ?? 'now' }}
                </div>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="st-card">
            <div class="empty-state">
                <div class="empty-state-icon"><i class="bi bi-layers"></i></div>
                <p>No flashcards yet.<br>
                <a href="#addCardForm" onclick="document.getElementById('addCardForm').classList.add('show')">Create your first card!</a></p>
            </div>
        </div>
    </div>
    @endforelse
</div>

<div style="margin-top:1.5rem">{{ $flashcards->links() }}</div>
@endsection
