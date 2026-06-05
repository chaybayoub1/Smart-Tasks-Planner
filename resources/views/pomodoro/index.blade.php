{{-- resources/views/pomodoro/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Pomodoro Timer')
@section('page-title', 'Pomodoro Timer')

@section('content')
<div class="row g-4">

    {{-- ── TIMER CARD ────────────────────────────────────────── --}}
    <div class="col-lg-5">
        <div class="card text-center">
            <div class="card-body py-4">

                {{-- Mode tabs --}}
                <div class="btn-group btn-group-sm mb-4 w-100" role="group" id="modeGroup">
                    <button type="button" class="btn btn-primary active"
                            data-mode="focus" data-mins="25">
                        🍅 Focus (25m)
                    </button>
                    <button type="button" class="btn btn-outline-primary"
                            data-mode="short_break" data-mins="5">
                        ☕ Short (5m)
                    </button>
                    <button type="button" class="btn btn-outline-primary"
                            data-mode="long_break" data-mins="15">
                        🛋️ Long (15m)
                    </button>
                </div>

                {{-- SVG ring + time display --}}
                <div class="position-relative d-inline-flex align-items-center
                            justify-content-center mb-4"
                     style="width:220px;height:220px">
                    <svg width="220" height="220"
                         style="position:absolute;top:0;left:0;transform:rotate(-90deg)">
                        <circle cx="110" cy="110" r="100"
                                fill="none" stroke="#e9ecef" stroke-width="10"/>
                        <circle id="timerRing" cx="110" cy="110" r="100"
                                fill="none" stroke="#6366f1" stroke-width="10"
                                stroke-linecap="round"
                                stroke-dasharray="628.3"
                                stroke-dashoffset="0"
                                style="transition:stroke-dashoffset .5s linear,stroke .3s"/>
                    </svg>
                    <div>
                        <div id="timerDisplay"
                             style="font-size:3.8rem;font-weight:800;
                                    letter-spacing:-.04em;color:#1e1b4b;line-height:1">
                            25:00
                        </div>
                        <div id="timerMode" class="text-muted small mt-1">
                            Focus Session
                        </div>
                    </div>
                </div>

                {{-- Background indicator --}}
                <div id="bgIndicator" class="alert alert-warning py-1 px-3 small mb-3"
                     style="display:none">
                    ⚡ Timer running in background — stays accurate!
                </div>

                {{-- Subject picker --}}
                <div class="mb-3 text-start">
                    <label class="form-label small fw-500 text-muted">
                        Study Subject (optional)
                    </label>
                    <select id="subjectSelect" class="form-select form-select-sm">
                        <option value="">— No subject —</option>
                        @foreach($subjects as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Task picker --}}
                <div class="mb-3 text-start">
                    <label class="form-label small fw-500 text-muted">
                        Link to Task (optional)
                    </label>
                    <select id="taskSelect" class="form-select form-select-sm">
                        <option value="">No task</option>
                        @foreach($tasks as $task)
                            <option value="{{ $task->id }}">{{ $task->title }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Custom duration --}}
                <div class="mb-4 text-start">
                    <label class="form-label small fw-500 text-muted">
                        Custom duration (min)
                    </label>
                    <input type="number" id="customMins" class="form-control form-control-sm"
                           min="1" max="120" placeholder="Default: 25">
                </div>

                {{-- Controls --}}
                <div class="d-flex justify-content-center gap-3">
                    <button id="btnStart"
                            class="btn btn-primary btn-lg px-4 fw-600"
                            style="min-width:130px">
                        <i class="bi bi-play-fill me-1"></i>Start
                    </button>
                    <button id="btnReset" class="btn btn-light btn-lg px-3"
                            title="Reset timer">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </button>
                </div>

                {{-- Session dots --}}
                <div class="mt-3 d-flex justify-content-center gap-2"
                     id="sessionDots"></div>
                <small class="text-muted d-block mt-1" id="sessionLabel">
                    Session 1 of 4
                </small>
            </div>
        </div>

        {{-- XP Banner --}}
        <div class="card mt-3" id="xpCard" style="display:none">
            <div class="card-body text-center py-2">
                <span class="fs-4">🎉</span>
                <span class="fw-600 ms-2" id="xpEarnedText">+10 XP earned!</span>
            </div>
        </div>

        {{-- Today stats --}}
        <div class="card mt-3">
            <div class="card-body py-3">
                <div class="row text-center g-0">
                    <div class="col border-end">
                        <div class="fw-700 fs-4 text-primary" id="todayMins">
                            {{ $todayMinutes }}
                        </div>
                        <div class="small text-muted">mins today</div>
                    </div>
                    <div class="col border-end">
                        <div class="fw-700 fs-4 text-success">{{ $totalSessions }}</div>
                        <div class="small text-muted">total sessions</div>
                    </div>
                    <div class="col">
                        <div class="fw-700 fs-4 text-warning">
                            {{ auth()->user()->level }}
                        </div>
                        <div class="small text-muted">level</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── RECENT SESSIONS ────────────────────────────────────── --}}
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clock-history text-primary me-2"></i>Recent Sessions
            </div>
            <div class="card-body p-0" id="sessionsContainer">
                @forelse($recentSessions as $session)
                <div class="d-flex align-items-center gap-3 px-3 py-2 border-bottom">
                    <div class="text-center" style="width:44px">
                        @if($session->type === 'focus') 🍅
                        @elseif($session->type === 'short_break') ☕
                        @else 🛋️
                        @endif
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-500 small">
                            {{ ucfirst(str_replace('_',' ',$session->type)) }}
                            @if($session->subject)
                                — <span style="color:{{ $session->subject->color }}">
                                    {{ $session->subject->name }}
                                  </span>
                            @endif
                            @if($session->task)
                                — <span class="text-muted">{{ $session->task->title }}</span>
                            @endif
                        </div>
                        <div class="text-muted" style="font-size:.75rem">
                            {{ $session->created_at->diffForHumans() }}
                            · {{ $session->duration }} min
                        </div>
                    </div>
                    <div class="text-end">
                        @if($session->completed)
                            <span class="badge text-bg-success">✓ Done</span>
                        @else
                            <span class="badge text-bg-secondary">Abandoned</span>
                        @endif
                        @if($session->xp_earned > 0)
                            <div class="small text-warning fw-600 mt-1">
                                +{{ $session->xp_earned }} XP
                            </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-stopwatch fs-1 d-block mb-3"></i>
                    No sessions yet. Start your first Pomodoro!
                </div>
                @endforelse
            </div>
        </div>

        {{-- Tips --}}
        <div class="card mt-3">
            <div class="card-body py-3">
                <h6 class="fw-600 mb-2">
                    <i class="bi bi-lightbulb-fill text-warning me-2"></i>Pomodoro Tips
                </h6>
                <ul class="mb-0 small text-muted ps-3" style="line-height:2">
                    <li>Work <strong>25 min</strong>, then take a <strong>5-min break</strong></li>
                    <li>After <strong>4 sessions</strong>, take a <strong>15-min break</strong></li>
                    <li>Each focus session earns <strong>10 XP</strong> 🎯</li>
                    <li>You can <strong>switch tabs freely</strong> — timer stays accurate ✅</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// ═══════════════════════════════════════════════════════════════
//  ROBUST POMODORO TIMER
//  Uses Date.now() timestamps — immune to tab throttling
// ═══════════════════════════════════════════════════════════════

const CSRF = document.querySelector('meta[name="csrf-token"]').content;
const CIRCUMFERENCE = 628.3;
const STORAGE_KEY   = 'studyflow_pomodoro';

// ── Mode config ────────────────────────────────────────────────
const MODES = {
    focus:       { label: '🍅 Focus Session',  color: '#6366f1', defaultMins: 25 },
    short_break: { label: '☕ Short Break',     color: '#10b981', defaultMins: 5  },
    long_break:  { label: '🛋️ Long Break',     color: '#3b82f6', defaultMins: 15 },
};

// ── State ──────────────────────────────────────────────────────
let state = {
    mode:          'focus',
    totalSeconds:  25 * 60,
    endTime:       null,       // timestamp (ms) when timer will reach 0
    pausedRemaining: null,     // seconds left when paused
    isRunning:     false,
    sessionCount:  0,
    startedAt:     null,
};

let rafId = null;             // requestAnimationFrame id

// ── DOM refs ───────────────────────────────────────────────────
const elDisplay  = document.getElementById('timerDisplay');
const elMode     = document.getElementById('timerMode');
const elRing     = document.getElementById('timerRing');
const elBtnStart = document.getElementById('btnStart');
const elBtnReset = document.getElementById('btnReset');
const elModeGrp  = document.getElementById('modeGroup');
const elDots     = document.getElementById('sessionDots');
const elSessLbl  = document.getElementById('sessionLabel');
const elXpCard   = document.getElementById('xpCard');
const elXpText   = document.getElementById('xpEarnedText');
const elBgInd    = document.getElementById('bgIndicator');

// ── Helpers ────────────────────────────────────────────────────
function secondsLeft() {
    if (!state.isRunning) return state.pausedRemaining ?? state.totalSeconds;
    const diff = Math.round((state.endTime - Date.now()) / 1000);
    return Math.max(0, diff);
}

function fmt(secs) {
    const m = String(Math.floor(secs / 60)).padStart(2, '0');
    const s = String(secs % 60).padStart(2, '0');
    return `${m}:${s}`;
}

// ── Render loop (requestAnimationFrame — not throttled) ────────
function tick() {
    const remaining = secondsLeft();

    // Update display
    elDisplay.textContent  = fmt(remaining);
    document.title         = `${fmt(remaining)} — StudyFlow`;

    // Update ring
    const progress = remaining / state.totalSeconds;
    elRing.style.strokeDashoffset = CIRCUMFERENCE * (1 - progress);
    elRing.style.stroke = MODES[state.mode].color;

    // Check completion
    if (state.isRunning && remaining <= 0) {
        onComplete();
        return;
    }

    rafId = requestAnimationFrame(tick);
}

function startRaf() {
    if (rafId) cancelAnimationFrame(rafId);
    rafId = requestAnimationFrame(tick);
}

function stopRaf() {
    if (rafId) cancelAnimationFrame(rafId);
    rafId = null;
}

// ── Controls ───────────────────────────────────────────────────
function startTimer() {
    if (state.isRunning) {
        // PAUSE — save remaining seconds
        state.pausedRemaining = secondsLeft();
        state.isRunning = false;
        stopRaf();
        tick(); // render once while paused
        elBtnStart.innerHTML = '<i class="bi bi-play-fill me-1"></i>Resume';
        elBtnStart.className = 'btn btn-success btn-lg px-4 fw-600';
        saveState();
        return;
    }

    // START / RESUME
    const secsToRun = state.pausedRemaining ?? state.totalSeconds;
    state.endTime   = Date.now() + secsToRun * 1000;
    state.isRunning = true;
    state.pausedRemaining = null;

    if (!state.startedAt) state.startedAt = new Date().toISOString();

    elBtnStart.innerHTML = '<i class="bi bi-pause-fill me-1"></i>Pause';
    elBtnStart.className = 'btn btn-warning btn-lg px-4 fw-600';

    saveState();
    startRaf();
}

function resetTimer() {
    // Save abandoned session if running and >60s elapsed
    if (state.isRunning) {
        const elapsed = state.totalSeconds - secondsLeft();
        if (state.mode === 'focus' && elapsed >= 60) {
            saveSession(false, elapsed);
        }
    }

    stopRaf();
    state.isRunning       = false;
    state.pausedRemaining = null;
    state.startedAt       = null;
    state.endTime         = null;

    // Respect custom duration
    const custom = parseInt(document.getElementById('customMins').value);
    state.totalSeconds = (!isNaN(custom) && custom > 0)
        ? custom * 60
        : MODES[state.mode].defaultMins * 60;

    clearState();
    tick();

    elBtnStart.innerHTML = '<i class="bi bi-play-fill me-1"></i>Start';
    elBtnStart.className = 'btn btn-primary btn-lg px-4 fw-600';
}

function switchMode(mode) {
    resetTimer();
    state.mode = mode;
    elMode.textContent = MODES[mode].label;
    const custom = parseInt(document.getElementById('customMins').value);
    state.totalSeconds = (!isNaN(custom) && custom > 0)
        ? custom * 60
        : MODES[mode].defaultMins * 60;
    tick();
}

// ── Completion ─────────────────────────────────────────────────
async function onComplete() {
    stopRaf();
    state.isRunning = false;
    state.pausedRemaining = null;
    clearState();

    playBeep();
    elBtnStart.innerHTML = '<i class="bi bi-play-fill me-1"></i>Start';
    elBtnStart.className = 'btn btn-primary btn-lg px-4 fw-600';
    elDisplay.textContent = '00:00';
    elRing.style.strokeDashoffset = CIRCUMFERENCE;

    const xp = await saveSession(true, state.totalSeconds);

    if (state.mode === 'focus') {
        state.sessionCount++;
        renderDots();
        showXpBanner(xp);

        // Update today counter
        const el = document.getElementById('todayMins');
        if (el) el.textContent = parseInt(el.textContent) + Math.round(state.totalSeconds / 60);
    }

    state.startedAt = null;

    // Flash title
    let f = 0;
    const iv = setInterval(() => {
        document.title = f++ % 2 === 0 ? '✅ Done!' : '🔔 StudyFlow';
        if (f > 8) { clearInterval(iv); document.title = 'StudyFlow'; }
    }, 500);

    // Show browser notification if permitted
    showNotification();
}

// ── API ────────────────────────────────────────────────────────
async function saveSession(completed, durationSeconds) {
    try {
        const res = await fetch('{{ route("pomodoro.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'Accept':       'application/json',
            },
            body: JSON.stringify({
                subject_id: document.getElementById('subjectSelect').value || null,
                task_id:    document.getElementById('taskSelect').value || null,
                duration:   Math.max(1, Math.round(durationSeconds / 60)),
                type:       state.mode,
                completed,
                started_at: state.startedAt,
            }),
        });
        const data = await res.json();
        if (completed) prependRow(data, durationSeconds);
        return data.xp_earned ?? 0;
    } catch(e) {
        console.error('Failed to save session', e);
        return 0;
    }
}

function prependRow(data, durationSeconds) {
    const c     = document.getElementById('sessionsContainer');
    const empty = c.querySelector('.text-center');
    if (empty) empty.remove();

    const emoji = state.mode === 'focus' ? '🍅'
                : state.mode === 'short_break' ? '☕' : '🛋️';
    const name  = state.mode.replace('_', ' ')
                            .replace(/\b\w/g, ch => ch.toUpperCase());
    const sel   = document.getElementById('subjectSelect');
    const subj  = sel.selectedIndex > 0 ? sel.options[sel.selectedIndex].text : '';
    const taskSel = document.getElementById('taskSelect');
    const task = taskSel.selectedIndex > 0 ? taskSel.options[taskSel.selectedIndex].text : '';

    const row = document.createElement('div');
    row.className = 'd-flex align-items-center gap-3 px-3 py-2 border-bottom';
    row.style.background = '#f0fdf4';
    row.innerHTML = `
        <div class="text-center" style="width:44px">${emoji}</div>
        <div class="flex-grow-1">
            <div class="fw-500 small">${name}${subj ? ` — ${subj}` : ''}${task ? ` — ${task}` : ''}</div>
            <div class="text-muted" style="font-size:.75rem">
                Just now · ${Math.round(durationSeconds / 60)} min
            </div>
        </div>
        <div class="text-end">
            <span class="badge text-bg-success">✓ Done</span>
            ${data.xp_earned > 0
                ? `<div class="small text-warning fw-600 mt-1">+${data.xp_earned} XP</div>`
                : ''}
        </div>`;
    c.prepend(row);
    setTimeout(() => row.style.background = '', 2000);
}

// ── UI helpers ─────────────────────────────────────────────────
function renderDots() {
    elDots.innerHTML = '';
    for (let i = 0; i < 4; i++) {
        const d = document.createElement('span');
        d.style.cssText = `
            display:inline-block;width:12px;height:12px;border-radius:50%;
            background:${i < state.sessionCount % 4 ? '#6366f1' : '#e2e8f0'};
            transition:background .3s`;
        elDots.appendChild(d);
    }
    elSessLbl.textContent = `Session ${(state.sessionCount % 4) + 1} of 4`;
}

function showXpBanner(xp) {
    if (!xp) return;
    elXpText.textContent = `+${xp} XP earned! 🎉`;
    elXpCard.style.display = 'block';
    setTimeout(() => elXpCard.style.display = 'none', 4000);
}

function playBeep() {
    try {
        const ctx = new (window.AudioContext || window.webkitAudioContext)();
        [0, 250, 500].forEach(delay => {
            const o = ctx.createOscillator();
            const g = ctx.createGain();
            o.connect(g); g.connect(ctx.destination);
            o.frequency.value = 880;
            g.gain.setValueAtTime(0.3, ctx.currentTime + delay / 1000);
            g.gain.exponentialRampToValueAtTime(0.001,
                ctx.currentTime + delay / 1000 + 0.3);
            o.start(ctx.currentTime + delay / 1000);
            o.stop(ctx.currentTime + delay / 1000 + 0.35);
        });
    } catch(e) {}
}

function showNotification() {
    if (!('Notification' in window)) return;
    const send = () => {
        const msg = state.mode === 'focus'
            ? '🍅 Focus session complete! Time for a break.'
            : '☕ Break over! Ready to focus?';
        new Notification('StudyFlow', { body: msg, icon: '/favicon.ico' });
    };
    if (Notification.permission === 'granted') {
        send();
    } else if (Notification.permission !== 'denied') {
        Notification.requestPermission().then(p => { if (p === 'granted') send(); });
    }
}

// ── Page Visibility API — recalculate on tab return ────────────
document.addEventListener('visibilitychange', () => {
    if (document.hidden) {
        // Tab going to background
        elBgInd.style.display = state.isRunning ? 'block' : 'none';
    } else {
        // Tab coming back to foreground
        elBgInd.style.display = 'none';

        if (state.isRunning) {
            const remaining = secondsLeft();
            if (remaining <= 0) {
                // Timer finished while away — complete immediately
                onComplete();
            } else {
                // Snap display to correct time instantly
                elDisplay.textContent = fmt(remaining);
                startRaf(); // resume animation loop
            }
        }
    }
});

// ── localStorage persistence (survive page refresh) ───────────
function saveState() {
    if (!state.isRunning) return;
    localStorage.setItem(STORAGE_KEY, JSON.stringify({
        mode:        state.mode,
        totalSeconds:state.totalSeconds,
        endTime:     state.endTime,
        startedAt:   state.startedAt,
        sessionCount:state.sessionCount,
    }));
}

function clearState() {
    localStorage.removeItem(STORAGE_KEY);
}

function restoreState() {
    try {
        const saved = JSON.parse(localStorage.getItem(STORAGE_KEY));
        if (!saved) return false;
        if (saved.endTime < Date.now()) {
            // Timer already expired while page was closed
            clearState();
            return false;
        }
        // Restore running timer
        state.mode         = saved.mode;
        state.totalSeconds = saved.totalSeconds;
        state.endTime      = saved.endTime;
        state.startedAt    = saved.startedAt;
        state.sessionCount = saved.sessionCount;
        state.isRunning    = true;

        // Update mode buttons UI
        elModeGrp.querySelectorAll('button').forEach(b => {
            b.className = b.dataset.mode === state.mode
                ? 'btn btn-primary active'
                : 'btn btn-outline-primary';
        });
        elMode.textContent = MODES[state.mode].label;

        elBtnStart.innerHTML = '<i class="bi bi-pause-fill me-1"></i>Pause';
        elBtnStart.className = 'btn btn-warning btn-lg px-4 fw-600';
        elBgInd.style.display = 'block';
        return true;
    } catch(e) {
        clearState();
        return false;
    }
}

// ── Event listeners ────────────────────────────────────────────
elBtnStart.addEventListener('click', startTimer);
elBtnReset.addEventListener('click', resetTimer);

elModeGrp.querySelectorAll('button').forEach(btn => {
    btn.addEventListener('click', () => {
        elModeGrp.querySelectorAll('button').forEach(b => {
            b.className = 'btn btn-outline-primary';
        });
        btn.className = 'btn btn-primary active';
        switchMode(btn.dataset.mode);
    });
});

document.getElementById('customMins').addEventListener('change', () => {
    if (!state.isRunning) resetTimer();
});

// Save state every 5 seconds while running
setInterval(() => { if (state.isRunning) saveState(); }, 5000);

// ── Init ───────────────────────────────────────────────────────
renderDots();

// Request notification permission on load
if ('Notification' in window && Notification.permission === 'default') {
    Notification.requestPermission();
}

// Restore saved timer or show default
if (!restoreState()) {
    tick(); // render initial state (paused)
} else {
    startRaf(); // resume
}
</script>
@endpush
