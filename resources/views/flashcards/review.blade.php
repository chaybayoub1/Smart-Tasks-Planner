{{-- resources/views/flashcards/review.blade.php --}}
@extends('layouts.app')
@section('title', 'Review Flashcards')
@section('page-title', 'Flashcard Review')

@section('content')

{{-- ── FILTER BAR ────────────────────────────────────────────── --}}
<div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
    <form method="GET" class="d-flex gap-2">
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
    </form>
    <span class="badge text-bg-secondary">
        {{ count($cards) }} card{{ count($cards) != 1 ? 's' : '' }} to review
    </span>
    <a href="{{ route('flashcards.index') }}" class="btn btn-sm btn-light ms-auto">
        ← All Cards
    </a>
</div>

@if($cards->isEmpty())
{{-- Nothing to review --}}
<div class="card">
    <div class="card-body text-center py-5">
        <div class="fs-1 mb-3">🎉</div>
        <h4 class="fw-700">All caught up!</h4>
        <p class="text-muted">No cards are due for review right now.</p>
        <a href="{{ route('flashcards.index') }}" class="btn btn-primary">
            Back to Flashcards
        </a>
    </div>
</div>
@else

{{-- ── REVIEW INTERFACE ──────────────────────────────────────── --}}
<div class="row justify-content-center">
    <div class="col-lg-7">

        {{-- Progress bar --}}
        <div class="mb-3">
            <div class="d-flex justify-content-between small text-muted mb-1">
                <span id="progressLabel">Card 1 of {{ count($cards) }}</span>
                <span id="statsLabel">✅ 0 &nbsp; ⚠️ 0 &nbsp; ❌ 0</span>
            </div>
            <div class="progress" style="height:8px;border-radius:99px">
                <div id="progressBar" class="progress-bar"
                     style="width:0%;background:linear-gradient(90deg,#6366f1,#8b5cf6);
                            border-radius:99px;transition:width .4s ease"></div>
            </div>
        </div>

        {{-- Card area --}}
        <div id="cardArea">
            {{-- Cards are rendered by JS from the data below --}}
        </div>

        {{-- Completed screen (hidden initially) --}}
        <div id="completedScreen" style="display:none">
            <div class="card text-center">
                <div class="card-body py-5">
                    <div class="fs-1 mb-3">🏆</div>
                    <h3 class="fw-700">Review Complete!</h3>
                    <p class="text-muted mb-4">Great job finishing your review session.</p>
                    <div class="d-flex justify-content-center gap-3 mb-4">
                        <div class="text-center px-3">
                            <div class="fs-2 fw-700 text-success" id="finalEasy">0</div>
                            <small class="text-muted">Easy</small>
                        </div>
                        <div class="text-center px-3 border-start border-end">
                            <div class="fs-2 fw-700 text-warning" id="finalMedium">0</div>
                            <small class="text-muted">Medium</small>
                        </div>
                        <div class="text-center px-3">
                            <div class="fs-2 fw-700 text-danger" id="finalHard">0</div>
                            <small class="text-muted">Hard</small>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center gap-2">
                        <button onclick="restartReview()" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-repeat me-1"></i>Review Again
                        </button>
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                            <i class="bi bi-grid me-1"></i>Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Pass card data to JS --}}
<script id="cardsData" type="application/json">
@json($cards->map(fn($c) => [
    'id'         => $c->id,
    'question'   => $c->question,
    'answer'     => $c->answer,
    'difficulty' => $c->difficulty,
    'subject'    => $c->subject?->name,
    'color'      => $c->subject?->color ?? '#6366f1',
    'review_count' => $c->review_count,
    'route'      => route('flashcards.difficulty', $c->id),
]))
</script>
@endif

@endsection

@push('styles')
<style>
.flip-card { perspective: 1200px; cursor: pointer; user-select: none; }
.flip-card-inner {
    position: relative; width: 100%; transition: transform .55s ease;
    transform-style: preserve-3d;
}
.flip-card.flipped .flip-card-inner { transform: rotateY(180deg); }
.flip-card-front, .flip-card-back {
    backface-visibility: hidden; border-radius: 16px;
    min-height: 280px; display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    padding: 2.5rem; text-align: center;
    box-shadow: 0 4px 24px rgba(0,0,0,.09);
}
.flip-card-front { background: #fff; }
.flip-card-back  {
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: #fff;
    position: absolute; top: 0; left: 0; width: 100%;
    transform: rotateY(180deg);
}
.difficulty-btn {
    flex: 1; border-radius: 12px; font-weight: 600;
    padding: .75rem; transition: transform .1s, box-shadow .1s;
}
.difficulty-btn:active { transform: scale(.96); }
.keyboard-hint {
    font-size: .72rem; color: #aaa; text-align: center; margin-top: .5rem;
}
</style>
@endpush

@push('scripts')
<script>
const CSRF    = document.querySelector('meta[name="csrf-token"]').content;
const allCards = JSON.parse(document.getElementById('cardsData')?.textContent || '[]');
let cards     = [...allCards];
let current   = 0;
let flipped   = false;
let stats     = { easy: 0, medium: 0, hard: 0 };

function renderCard() {
    if (current >= cards.length) { showCompleted(); return; }

    const card    = cards[current];
    const area    = document.getElementById('cardArea');
    const pct     = Math.round((current / cards.length) * 100);
    document.getElementById('progressBar').style.width = pct + '%';
    document.getElementById('progressLabel').textContent = `Card ${current + 1} of ${cards.length}`;
    updateStats();

    flipped = false;
    area.innerHTML = `
    <div class="flip-card mb-4" id="flipCard" onclick="flipCard()">
        <div class="flip-card-inner">
            <div class="flip-card-front">
                ${card.subject ? `<span class="badge mb-3"
                    style="background:${card.color}22;color:${card.color};font-size:.75rem">
                    ${card.subject}</span>` : ''}
                <div class="text-muted small mb-2 fw-500">QUESTION</div>
                <div class="fw-600 fs-5" style="line-height:1.6">${escapeHtml(card.question)}</div>
                <div class="text-muted small mt-4">
                    <i class="bi bi-hand-index-thumb me-1"></i>Click to reveal answer
                </div>
            </div>
            <div class="flip-card-back">
                <div class="opacity-75 small mb-2 fw-500">ANSWER</div>
                <div class="fw-600 fs-5" style="line-height:1.6">${escapeHtml(card.answer)}</div>
                <div class="small mt-4 opacity-60">
                    Reviewed ${card.review_count}x
                </div>
            </div>
        </div>
    </div>

    <div id="ratingButtons" style="display:none">
        <p class="text-center text-muted small mb-2 fw-500">How well did you know this?</p>
        <div class="d-flex gap-2 mb-2">
            <button class="difficulty-btn btn btn-danger" onclick="rate('hard')">
                😰 Hard<br><small class="fw-400 opacity-75">Review soon</small>
            </button>
            <button class="difficulty-btn btn btn-warning text-dark" onclick="rate('medium')">
                🤔 Medium<br><small class="fw-400 opacity-75">Review in 2 days</small>
            </button>
            <button class="difficulty-btn btn btn-success" onclick="rate('easy')">
                😊 Easy<br><small class="fw-400 opacity-75">Review in 4 days</small>
            </button>
        </div>
        <div class="keyboard-hint">⬅ Hard &nbsp;·&nbsp; Space flip &nbsp;·&nbsp; 1/2/3 rate</div>
    </div>`;
}

function flipCard() {
    const fc = document.getElementById('flipCard');
    if (!fc) return;
    flipped = !flipped;
    fc.classList.toggle('flipped', flipped);
    if (flipped) {
        setTimeout(() => {
            document.getElementById('ratingButtons').style.display = 'block';
        }, 280);
    }
}

async function rate(difficulty) {
    const card = cards[current];
    stats[difficulty]++;
    updateStats();

    try {
        await fetch(card.route, {
            method: 'PATCH',
            headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ difficulty })
        });
    } catch(e) { console.error(e); }

    current++;
    renderCard();
}

function updateStats() {
    document.getElementById('statsLabel').textContent =
        `✅ ${stats.easy} &nbsp; ⚠️ ${stats.medium} &nbsp; ❌ ${stats.hard}`;
    document.getElementById('statsLabel').innerHTML =
        `✅ ${stats.easy} &nbsp; ⚠️ ${stats.medium} &nbsp; ❌ ${stats.hard}`;
}

function showCompleted() {
    document.getElementById('cardArea').style.display = 'none';
    document.getElementById('completedScreen').style.display = 'block';
    document.getElementById('progressBar').style.width = '100%';
    document.getElementById('progressLabel').textContent = `Done! ${cards.length} cards reviewed`;
    document.getElementById('finalEasy').textContent   = stats.easy;
    document.getElementById('finalMedium').textContent = stats.medium;
    document.getElementById('finalHard').textContent   = stats.hard;
}

function restartReview() {
    cards   = [...allCards];
    current = 0;
    stats   = { easy: 0, medium: 0, hard: 0 };
    document.getElementById('completedScreen').style.display = 'none';
    document.getElementById('cardArea').style.display = 'block';
    renderCard();
}

function escapeHtml(str) {
    return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

// Keyboard shortcuts
document.addEventListener('keydown', e => {
    if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
    if (e.code === 'Space') { e.preventDefault(); flipCard(); }
    if (flipped) {
        if (e.key === '1') rate('hard');
        if (e.key === '2') rate('medium');
        if (e.key === '3') rate('easy');
    }
});

// Init
if (allCards.length > 0) renderCard();
</script>
@endpush
