{{-- resources/views/flashcards/review.blade.php --}}
@extends('layouts.app')
@section('title', 'Review Flashcards')
@section('page-title', 'Flashcard Review')

@section('content')

{{-- ── FILTER BAR ────────────────────────────────────────────── --}}
<div style="display:flex;align-items:center;gap:.75rem;margin-bottom:1.5rem;flex-wrap:wrap">
    <form method="GET" style="display:flex;gap:.5rem">
        <select name="subject_id" class="st-select" style="width:auto;min-width:140px" onchange="this.form.submit()">
            <option value="">All Subjects</option>
            @foreach($subjects as $s)
                <option value="{{ $s->id }}" {{ request('subject_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
            @endforeach
        </select>
    </form>
    <span class="tag tag-muted">{{ count($cards) }} card{{ count($cards) != 1 ? 's' : '' }} to review</span>
    <a href="{{ route('flashcards.index') }}" class="btn-st-ghost" style="margin-left:auto;font-size:.78rem">
        <i class="bi bi-arrow-left"></i> All Cards
    </a>
</div>

@if($cards->isEmpty())
<div class="st-card">
    <div class="empty-state" style="padding:3.5rem 1rem">
        <div class="empty-state-icon" style="background:rgba(0,212,170,.1);color:var(--c-teal);width:64px;height:64px;font-size:1.8rem">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <div style="font-family:var(--font-display);font-size:1.3rem;font-weight:800;color:var(--c-text);margin-bottom:.5rem">All caught up!</div>
        <p>No cards are due for review right now.</p>
        <a href="{{ route('flashcards.index') }}" class="btn-st-primary" style="margin-top:1rem">Back to Flashcards</a>
    </div>
</div>
@else

<div class="row justify-content-center">
    <div class="col-lg-7">

        {{-- Progress --}}
        <div style="margin-bottom:1.25rem">
            <div style="display:flex;justify-content:space-between;margin-bottom:.5rem">
                <span style="font-size:.78rem;color:var(--c-muted2);font-weight:500" id="progressLabel">Card 1 of {{ count($cards) }}</span>
                <span style="font-size:.78rem;color:var(--c-muted2)" id="statsLabel">
                    <span style="color:var(--c-teal)">0 easy</span> &nbsp;
                    <span style="color:var(--c-amber)">0 medium</span> &nbsp;
                    <span style="color:var(--c-coral)">0 hard</span>
                </span>
            </div>
            <div class="st-progress">
                <div class="st-progress-fill teal" id="progressBar" style="width:0%"></div>
            </div>
        </div>

        {{-- Card area --}}
        <div id="cardArea"></div>

        {{-- Completed screen --}}
        <div id="completedScreen" style="display:none">
            <div class="st-card" style="text-align:center;border-color:rgba(0,212,170,.25)">
                <div style="padding:3rem 2rem">
                    <div style="width:64px;height:64px;border-radius:16px;background:rgba(0,212,170,.1);border:1px solid rgba(0,212,170,.25);display:flex;align-items:center;justify-content:center;font-size:2rem;margin:0 auto 1.25rem">
                        <i class="bi bi-trophy-fill" style="color:var(--c-amber)"></i>
                    </div>
                    <div style="font-family:var(--font-display);font-size:1.5rem;font-weight:800;color:var(--c-text);margin-bottom:.5rem">Review Complete!</div>
                    <p style="color:var(--c-muted);margin-bottom:1.75rem;font-size:.88rem">Great job finishing your review session.</p>

                    <div style="display:flex;justify-content:center;gap:1px;margin-bottom:1.75rem;background:var(--c-border);border-radius:12px;overflow:hidden">
                        <div style="flex:1;padding:1rem;background:var(--c-surface);text-align:center">
                            <div style="font-family:var(--font-display);font-size:2rem;font-weight:800;color:var(--c-teal)" id="finalEasy">0</div>
                            <div style="font-size:.72rem;color:var(--c-muted);text-transform:uppercase;letter-spacing:.06em">Easy</div>
                        </div>
                        <div style="flex:1;padding:1rem;background:var(--c-surface);text-align:center">
                            <div style="font-family:var(--font-display);font-size:2rem;font-weight:800;color:var(--c-amber)" id="finalMedium">0</div>
                            <div style="font-size:.72rem;color:var(--c-muted);text-transform:uppercase;letter-spacing:.06em">Medium</div>
                        </div>
                        <div style="flex:1;padding:1rem;background:var(--c-surface);text-align:center">
                            <div style="font-family:var(--font-display);font-size:2rem;font-weight:800;color:var(--c-coral)" id="finalHard">0</div>
                            <div style="font-size:.72rem;color:var(--c-muted);text-transform:uppercase;letter-spacing:.06em">Hard</div>
                        </div>
                    </div>

                    <div style="display:flex;justify-content:center;gap:.75rem;flex-wrap:wrap">
                        <button onclick="restartReview()" class="btn-st-ghost">
                            <i class="bi bi-arrow-repeat"></i> Review Again
                        </button>
                        <a href="{{ route('dashboard') }}" class="btn-st-primary">
                            <i class="bi bi-grid"></i> Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script id="cardsData" type="application/json">
@json($cards->map(fn($c) => [
    'id'           => $c->id,
    'question'     => $c->question,
    'answer'       => $c->answer,
    'difficulty'   => $c->difficulty,
    'subject'      => $c->subject?->name,
    'color'        => $c->subject?->color ?? '#00d4aa',
    'review_count' => $c->review_count,
    'route'        => route('flashcards.difficulty', $c->id),
]))
</script>
@endif

@endsection

@push('scripts')
@if(!$cards->isEmpty())
<script>
const CSRF     = document.querySelector('meta[name="csrf-token"]').content;
const allCards = JSON.parse(document.getElementById('cardsData')?.textContent || '[]');
let cards   = [...allCards];
let current = 0, flipped = false;
let stats   = { easy: 0, medium: 0, hard: 0 };

function renderCard() {
    if (current >= cards.length) { showCompleted(); return; }
    const card = cards[current];
    const pct  = Math.round((current / cards.length) * 100);
    document.getElementById('progressBar').style.width = pct + '%';
    document.getElementById('progressLabel').textContent = `Card ${current + 1} of ${cards.length}`;
    updateStats();
    flipped = false;

    document.getElementById('cardArea').innerHTML = `
    <div class="flip-card" id="flipCard" onclick="flipCard()" style="margin-bottom:1rem;cursor:pointer;user-select:none">
        <div class="flip-card-inner" style="position:relative;min-height:260px">

            <div class="flip-card-front" style="background:var(--c-surface2);border:1px solid var(--c-border2);border-radius:var(--radius-lg);min-height:260px;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:2.5rem;text-align:center;backface-visibility:hidden">
                ${card.subject ? `<span class="tag" style="background:${card.color}18;color:${card.color};border-color:${card.color}30;margin-bottom:1rem;font-size:.7rem">${escHtml(card.subject)}</span>` : ''}
                <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--c-muted);margin-bottom:.75rem">Question</div>
                <div style="font-size:1.1rem;font-weight:600;color:var(--c-text);line-height:1.6">${escHtml(card.question)}</div>
                <div style="margin-top:1.5rem;font-size:.78rem;color:var(--c-muted)">
                    <i class="bi bi-hand-index-thumb" style="margin-right:.3rem"></i>Click to reveal answer
                </div>
            </div>

            <div class="flip-card-back" style="position:absolute;top:0;left:0;width:100%;background:linear-gradient(135deg,rgba(0,212,170,.12),rgba(124,106,247,.12));border:1px solid rgba(0,212,170,.3);border-radius:var(--radius-lg);min-height:260px;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:2.5rem;text-align:center;backface-visibility:hidden;transform:rotateY(180deg)">
                <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--c-teal);margin-bottom:.75rem">Answer</div>
                <div style="font-size:1.1rem;font-weight:600;color:var(--c-text);line-height:1.6">${escHtml(card.answer)}</div>
                <div style="margin-top:1rem;font-size:.72rem;color:var(--c-muted)">Reviewed ${card.review_count}x</div>
            </div>
        </div>
    </div>

    <div id="ratingButtons" style="display:none">
        <p style="text-align:center;font-size:.78rem;color:var(--c-muted2);font-weight:600;margin-bottom:.75rem;text-transform:uppercase;letter-spacing:.06em">How well did you know this?</p>
        <div style="display:flex;gap:.6rem;margin-bottom:.6rem">
            <button onclick="rate('hard')" style="flex:1;padding:.85rem .5rem;border-radius:var(--radius-md);border:1px solid rgba(255,107,107,.3);background:rgba(255,107,107,.1);color:var(--c-coral);font-weight:700;font-size:.85rem;cursor:pointer;transition:all .15s;font-family:var(--font-body)">
                Hard<br><span style="font-size:.7rem;font-weight:400;opacity:.75">Review soon</span>
            </button>
            <button onclick="rate('medium')" style="flex:1;padding:.85rem .5rem;border-radius:var(--radius-md);border:1px solid rgba(245,158,11,.3);background:rgba(245,158,11,.1);color:var(--c-amber);font-weight:700;font-size:.85rem;cursor:pointer;transition:all .15s;font-family:var(--font-body)">
                Medium<br><span style="font-size:.7rem;font-weight:400;opacity:.75">2 days</span>
            </button>
            <button onclick="rate('easy')" style="flex:1;padding:.85rem .5rem;border-radius:var(--radius-md);border:1px solid rgba(0,212,170,.3);background:rgba(0,212,170,.1);color:var(--c-teal);font-weight:700;font-size:.85rem;cursor:pointer;transition:all .15s;font-family:var(--font-body)">
                Easy<br><span style="font-size:.7rem;font-weight:400;opacity:.75">4 days</span>
            </button>
        </div>
        <div style="text-align:center;font-size:.7rem;color:var(--c-muted)">Space = flip &nbsp;&middot;&nbsp; 1 Hard &nbsp;&middot;&nbsp; 2 Medium &nbsp;&middot;&nbsp; 3 Easy</div>
    </div>`;
}

function flipCard() {
    const fc = document.getElementById('flipCard');
    if (!fc) return;
    flipped = !flipped;
    fc.classList.toggle('flipped', flipped);
    if (flipped) setTimeout(() => { const rb = document.getElementById('ratingButtons'); if(rb) rb.style.display='block'; }, 280);
}

async function rate(difficulty) {
    const card = cards[current];
    stats[difficulty]++;
    updateStats();
    try {
        await fetch(card.route, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ difficulty })
        });
    } catch(e) {}
    current++;
    renderCard();
}

function updateStats() {
    document.getElementById('statsLabel').innerHTML =
        `<span style="color:var(--c-teal)">${stats.easy} easy</span> &nbsp; <span style="color:var(--c-amber)">${stats.medium} medium</span> &nbsp; <span style="color:var(--c-coral)">${stats.hard} hard</span>`;
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
    cards = [...allCards]; current = 0; stats = { easy:0, medium:0, hard:0 };
    document.getElementById('completedScreen').style.display = 'none';
    document.getElementById('cardArea').style.display = 'block';
    renderCard();
}

function escHtml(str) {
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

document.addEventListener('keydown', e => {
    if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
    if (e.code === 'Space') { e.preventDefault(); flipCard(); }
    if (flipped) {
        if (e.key === '1') rate('hard');
        if (e.key === '2') rate('medium');
        if (e.key === '3') rate('easy');
    }
});

if (allCards.length > 0) renderCard();
</script>
@endif
@endpush
