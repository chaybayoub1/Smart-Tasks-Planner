{{-- resources/views/pomodoro/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Pomodoro Timer')
@section('page-title', 'Pomodoro Timer')

@section('content')
<div class="row g-4">

    {{-- ── TIMER CARD ────────────────────────────────────────── --}}
    <div class="col-lg-5">
        <div class="st-card" style="text-align:center">
            <div style="padding:1.5rem 1.25rem 1.25rem">

                {{-- Mode tabs --}}
                <div style="display:flex;gap:.4rem;background:var(--c-surface2);border-radius:10px;padding:4px;margin-bottom:1.5rem" id="modeGroup">
                    <button type="button" class="mode-btn active" data-mode="focus" data-mins="25"
                            style="flex:1;padding:.45rem .6rem;border:none;border-radius:7px;font-size:.78rem;font-weight:600;cursor:pointer;transition:all .2s;background:var(--c-teal);color:var(--c-bg)">
                        Focus (25m)
                    </button>
                    <button type="button" class="mode-btn" data-mode="short_break" data-mins="5"
                            style="flex:1;padding:.45rem .6rem;border:none;border-radius:7px;font-size:.78rem;font-weight:600;cursor:pointer;transition:all .2s;background:transparent;color:var(--c-muted2)">
                        Short (5m)
                    </button>
                    <button type="button" class="mode-btn" data-mode="long_break" data-mins="15"
                            style="flex:1;padding:.45rem .6rem;border:none;border-radius:7px;font-size:.78rem;font-weight:600;cursor:pointer;transition:all .2s;background:transparent;color:var(--c-muted2)">
                        Long (15m)
                    </button>
                </div>

                {{-- SVG ring + time display --}}
                <div style="position:relative;display:inline-flex;align-items:center;justify-content:center;margin-bottom:1.5rem;width:220px;height:220px">
                    <svg width="220" height="220" style="position:absolute;top:0;left:0;transform:rotate(-90deg)">
                        <circle cx="110" cy="110" r="100" fill="none" stroke="rgba(255,255,255,.06)" stroke-width="10"/>
                        <circle id="timerRing" cx="110" cy="110" r="100"
                                fill="none" stroke="var(--c-teal)" stroke-width="10"
                                stroke-linecap="round"
                                stroke-dasharray="628.3"
                                stroke-dashoffset="0"
                                style="transition:stroke-dashoffset .5s linear,stroke .3s;filter:drop-shadow(0 0 8px var(--c-teal))"/>
                    </svg>
                    <div>
                        <div id="timerDisplay" style="font-family:var(--font-display);font-size:3.8rem;font-weight:800;letter-spacing:-.04em;color:var(--c-teal);line-height:1;text-shadow:0 0 30px rgba(0,212,170,.4)">
                            25:00
                        </div>
                        <div id="timerMode" style="color:var(--c-muted);font-size:.8rem;margin-top:.35rem">
                            Focus Session
                        </div>
                    </div>
                </div>

                {{-- Background indicator --}}
                <div id="bgIndicator" class="tag tag-amber" style="display:none;margin:0 auto .85rem;width:fit-content">
                    <i class="bi bi-lightning-charge-fill"></i> Timer running in background
                </div>

                {{-- Subject picker --}}
                <div style="margin-bottom:.85rem;text-align:left">
                    <label class="st-label">Study Subject (optional)</label>
                    <select id="subjectSelect" class="st-select">
                        <option value="">— No subject —</option>
                        @foreach($subjects as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Custom duration --}}
                <div style="margin-bottom:1.25rem;text-align:left">
                    <label class="st-label">Custom duration (min)</label>
                    <input type="number" id="customMins" class="st-input" min="1" max="120" placeholder="Default: 25">
                </div>

                {{-- Controls --}}
                <div style="display:flex;justify-content:center;gap:.75rem">
                    <button id="btnStart" class="btn-st-primary" style="min-width:140px;justify-content:center;padding:.7rem 1.5rem;font-size:.95rem">
                        <i class="bi bi-play-fill"></i> Start
                    </button>
                    <button id="btnReset" class="btn-st-ghost" style="padding:.7rem .9rem" title="Reset timer">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </button>
                </div>

                {{-- Session dots --}}
                <div style="margin-top:1rem;display:flex;justify-content:center;gap:.5rem" id="sessionDots"></div>
                <div style="font-size:.75rem;color:var(--c-muted);margin-top:.35rem" id="sessionLabel">Session 1 of 4</div>
            </div>
        </div>

        {{-- XP Banner --}}
        <div class="st-card" id="xpCard" style="display:none;margin-top:.75rem;text-align:center;padding:1rem;background:linear-gradient(135deg,rgba(0,212,170,.12),rgba(124,106,247,.12));border-color:rgba(0,212,170,.3)">
            <div style="font-family:var(--font-display);font-size:1.1rem;font-weight:800;color:var(--c-teal)" id="xpEarnedText">+10 XP earned!</div>
        </div>

        {{-- Today stats --}}
        <div class="st-card" style="margin-top:.75rem">
            <div style="display:grid;grid-template-columns:repeat(3,1fr);text-align:center;padding:.1rem">
                <div style="padding:1rem;border-right:1px solid var(--c-border)">
                    <div style="font-family:var(--font-display);font-size:1.6rem;font-weight:800;color:var(--c-teal)" id="todayMins">{{ $todayMinutes }}</div>
                    <div style="font-size:.72rem;color:var(--c-muted);text-transform:uppercase;letter-spacing:.05em">mins today</div>
                </div>
                <div style="padding:1rem;border-right:1px solid var(--c-border)">
                    <div style="font-family:var(--font-display);font-size:1.6rem;font-weight:800;color:var(--c-violet)">{{ $totalSessions }}</div>
                    <div style="font-size:.72rem;color:var(--c-muted);text-transform:uppercase;letter-spacing:.05em">sessions</div>
                </div>
                <div style="padding:1rem">
                    <div style="font-family:var(--font-display);font-size:1.6rem;font-weight:800;color:var(--c-amber)">{{ auth()->user()->level }}</div>
                    <div style="font-size:.72rem;color:var(--c-muted);text-transform:uppercase;letter-spacing:.05em">level</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── RECENT SESSIONS ────────────────────────────────────── --}}
    <div class="col-lg-7">
        <div class="st-card">
            <div class="st-card-header">
                <div class="st-card-title">
                    <div class="icon icon-violet"><i class="bi bi-clock-history"></i></div>
                    Recent Sessions
                </div>
            </div>
            <div id="sessionsContainer">
                @forelse($recentSessions as $session)
                <div class="st-list-item">
                    <div style="width:36px;height:36px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:1rem;
                        {{ $session->type === 'focus' ? 'background:rgba(0,212,170,.1);' : ($session->type === 'short_break' ? 'background:rgba(245,158,11,.1);' : 'background:rgba(124,106,247,.1);') }}">
                        @if($session->type === 'focus')
                            <i class="bi bi-bullseye" style="color:var(--c-teal)"></i>
                        @elseif($session->type === 'short_break')
                            <i class="bi bi-cup-hot-fill" style="color:var(--c-amber)"></i>
                        @else
                            <i class="bi bi-moon-stars-fill" style="color:var(--c-violet)"></i>
                        @endif
                    </div>
                    <div style="flex:1;min-width:0">
                        <div style="font-size:.85rem;font-weight:600;color:var(--c-text)">
                            {{ ucfirst(str_replace('_',' ',$session->type)) }}
                            @if($session->subject)
                                &mdash; <span style="color:{{ $session->subject->color }}">{{ $session->subject->name }}</span>
                            @endif
                        </div>
                        <div style="font-size:.73rem;color:var(--c-muted)">
                            {{ $session->created_at->diffForHumans() }} &middot; {{ $session->duration }} min
                        </div>
                    </div>
                    <div style="text-align:right;flex-shrink:0">
                        @if($session->completed)
                            <span class="tag tag-teal">Done</span>
                        @else
                            <span class="tag tag-muted">Abandoned</span>
                        @endif
                        @if($session->xp_earned > 0)
                            <div style="font-size:.73rem;color:var(--c-amber);font-weight:700;margin-top:.25rem">+{{ $session->xp_earned }} XP</div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <div class="empty-state-icon"><i class="bi bi-stopwatch"></i></div>
                    <p>No sessions yet.<br>Start your first Pomodoro!</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Tips --}}
        <div class="st-card" style="margin-top:.75rem">
            <div style="padding:1.1rem 1.25rem">
                <div style="font-family:var(--font-display);font-size:.82rem;font-weight:700;color:var(--c-muted2);text-transform:uppercase;letter-spacing:.08em;margin-bottom:.85rem">
                    <i class="bi bi-lightbulb-fill" style="color:var(--c-amber);margin-right:.4rem"></i>Pomodoro Tips
                </div>
                <div style="display:flex;flex-direction:column;gap:.55rem">
                    @foreach([
                        ['teal',   'bi-clock',           'Work 25 min, then take a 5-min break'],
                        ['violet', 'bi-arrow-repeat',    'After 4 sessions, take a 15-min long break'],
                        ['amber',  'bi-star-fill',       'Each focus session earns 10 XP'],
                        ['teal',   'bi-check-circle',    'You can switch tabs freely — timer stays accurate'],
                    ] as [$color, $icon, $text])
                    <div style="display:flex;align-items:flex-start;gap:.65rem">
                        <div style="width:22px;height:22px;border-radius:6px;background:rgba(0,212,170,.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:.05rem">
                            <i class="bi {{ $icon }}" style="font-size:.7rem;color:var(--c-{{ $color }})"></i>
                        </div>
                        <span style="font-size:.82rem;color:var(--c-muted2);line-height:1.5">{{ $text }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// ═══════════════════════════════════════════════════════════════
//  ROBUST POMODORO TIMER — all original logic preserved
// ═══════════════════════════════════════════════════════════════

const CSRF = document.querySelector('meta[name="csrf-token"]').content;
const CIRCUMFERENCE = 628.3;
const STORAGE_KEY   = 'studyflow_pomodoro';

const MODES = {
    focus:       { label: 'Focus Session',  color: 'var(--c-teal)',   defaultMins: 25 },
    short_break: { label: 'Short Break',    color: 'var(--c-amber)',  defaultMins: 5  },
    long_break:  { label: 'Long Break',     color: 'var(--c-violet)', defaultMins: 15 },
};

let state = {
    mode:            'focus',
    totalSeconds:    25 * 60,
    endTime:         null,
    pausedRemaining: null,
    isRunning:       false,
    sessionCount:    0,
    startedAt:       null,
};

let rafId = null;

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

function tick() {
    const remaining = secondsLeft();
    elDisplay.textContent = fmt(remaining);
    document.title = `${fmt(remaining)} — SmarTasker`;

    const progress = remaining / state.totalSeconds;
    elRing.style.strokeDashoffset = CIRCUMFERENCE * (1 - progress);
    elRing.style.stroke = MODES[state.mode].color;

    if (state.isRunning && remaining <= 0) {
        cancelAnimationFrame(rafId);
        onComplete();
        return;
    }
    if (state.isRunning) rafId = requestAnimationFrame(tick);
}

function startRaf() { cancelAnimationFrame(rafId); rafId = requestAnimationFrame(tick); }

function startTimer() {
    if (state.isRunning) {
        // Pause
        state.pausedRemaining = secondsLeft();
        state.isRunning = false;
        cancelAnimationFrame(rafId);
        elBtnStart.innerHTML = '<i class="bi bi-play-fill"></i> Resume';
        elBtnStart.style.background = 'linear-gradient(135deg,var(--c-amber),var(--c-coral))';
        clearState();
        return;
    }

    const custom = parseInt(document.getElementById('customMins').value);
    if (!isNaN(custom) && custom > 0 && state.pausedRemaining === null) {
        state.totalSeconds = custom * 60;
    }

    const secs = state.pausedRemaining ?? state.totalSeconds;
    state.endTime = Date.now() + secs * 1000;
    state.startedAt = state.startedAt ?? Date.now();
    state.isRunning = true;
    state.pausedRemaining = null;

    elBtnStart.innerHTML = '<i class="bi bi-pause-fill"></i> Pause';
    elBtnStart.style.background = 'linear-gradient(135deg,var(--c-amber),var(--c-coral))';
    startRaf();
}

function resetTimer() {
    cancelAnimationFrame(rafId);
    clearState();
    const custom = parseInt(document.getElementById('customMins').value);
    const def    = MODES[state.mode].defaultMins;
    state.totalSeconds    = (!isNaN(custom) && custom > 0 ? custom : def) * 60;
    state.endTime         = null;
    state.pausedRemaining = null;
    state.isRunning       = false;
    state.startedAt       = null;

    elBtnStart.innerHTML = '<i class="bi bi-play-fill"></i> Start';
    elBtnStart.style.background = '';
    document.title = 'SmarTasker';
    tick();
}

function switchMode(mode) {
    resetTimer();
    state.mode = mode;
    const custom = parseInt(document.getElementById('customMins').value);
    state.totalSeconds = (!isNaN(custom) && custom > 0 ? custom : MODES[mode].defaultMins) * 60;
    elMode.textContent = MODES[mode].label;
    tick();
}

async function onComplete() {
    state.isRunning = false;
    clearState();
    playBeep();
    showNotification();

    const elapsed = state.startedAt ? Math.round((Date.now() - state.startedAt) / 1000) : state.totalSeconds;
    state.startedAt = null;

    if (state.mode === 'focus') state.sessionCount++;
    renderDots();

    const payload = {
        type:       state.mode,
        duration:   Math.round(state.totalSeconds / 60),
        completed:  true,
        subject_id: document.getElementById('subjectSelect').value || null,
    };

    try {
        const res  = await fetch('{{ route("pomodoro.store") }}', {
            method: 'POST',
            headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify(payload),
        });
        const data = await res.json();
        if (data.xp_earned) showXpBanner(data.xp_earned);
        if (data.today_minutes != null) document.getElementById('todayMins').textContent = data.today_minutes;
        prependRow(data, elapsed);
    } catch(e) {}

    // Auto-advance mode
    if (state.mode === 'focus') {
        switchMode(state.sessionCount % 4 === 0 ? 'long_break' : 'short_break');
    } else {
        switchMode('focus');
    }

    elBtnStart.innerHTML = '<i class="bi bi-play-fill"></i> Start';
    elBtnStart.style.background = '';
}

function prependRow(data, durationSeconds) {
    const c = document.getElementById('sessionsContainer');
    const empty = c.querySelector('.empty-state');
    if (empty) empty.remove();

    const icons = { focus: 'bi-bullseye', short_break: 'bi-cup-hot-fill', long_break: 'bi-moon-stars-fill' };
    const colors = { focus: 'var(--c-teal)', short_break: 'var(--c-amber)', long_break: 'var(--c-violet)' };
    const bgcols = { focus: 'rgba(0,212,170,.1)', short_break: 'rgba(245,158,11,.1)', long_break: 'rgba(124,106,247,.1)' };
    const name  = state.mode.replace('_',' ').replace(/\b\w/g, ch => ch.toUpperCase());
    const sel   = document.getElementById('subjectSelect');
    const subj  = sel.selectedIndex > 0 ? sel.options[sel.selectedIndex].text : '';

    const row = document.createElement('div');
    row.className = 'st-list-item';
    row.style.background = 'rgba(0,212,170,.05)';
    row.innerHTML = `
        <div style="width:36px;height:36px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:${bgcols[state.mode]}">
            <i class="bi ${icons[state.mode]}" style="color:${colors[state.mode]}"></i>
        </div>
        <div style="flex:1;min-width:0">
            <div style="font-size:.85rem;font-weight:600;color:var(--c-text)">${name}${subj ? ` &mdash; ${subj}` : ''}</div>
            <div style="font-size:.73rem;color:var(--c-muted)">Just now &middot; ${Math.round(durationSeconds/60)} min</div>
        </div>
        <div style="text-align:right;flex-shrink:0">
            <span class="tag tag-teal">Done</span>
            ${data.xp_earned > 0 ? `<div style="font-size:.73rem;color:var(--c-amber);font-weight:700;margin-top:.25rem">+${data.xp_earned} XP</div>` : ''}
        </div>`;
    c.prepend(row);
    setTimeout(() => row.style.background = '', 2500);
}

function renderDots() {
    elDots.innerHTML = '';
    for (let i = 0; i < 4; i++) {
        const d = document.createElement('span');
        d.style.cssText = `display:inline-block;width:10px;height:10px;border-radius:50%;
            background:${i < state.sessionCount % 4 ? 'var(--c-teal)' : 'rgba(255,255,255,.1)'};
            transition:background .3s;box-shadow:${i < state.sessionCount % 4 ? '0 0 6px rgba(0,212,170,.5)' : 'none'}`;
        elDots.appendChild(d);
    }
    elSessLbl.textContent = `Session ${(state.sessionCount % 4) + 1} of 4`;
}

function showXpBanner(xp) {
    if (!xp) return;
    elXpText.textContent = `+${xp} XP earned!`;
    elXpCard.style.display = 'block';
    setTimeout(() => elXpCard.style.display = 'none', 4000);
}

function playBeep() {
    try {
        const ctx = new (window.AudioContext || window.webkitAudioContext)();
        [0, 250, 500].forEach(delay => {
            const o = ctx.createOscillator(), g = ctx.createGain();
            o.connect(g); g.connect(ctx.destination);
            o.frequency.value = 880;
            g.gain.setValueAtTime(0.3, ctx.currentTime + delay/1000);
            g.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + delay/1000 + 0.3);
            o.start(ctx.currentTime + delay/1000);
            o.stop(ctx.currentTime + delay/1000 + 0.35);
        });
    } catch(e) {}
}

function showNotification() {
    if (!('Notification' in window)) return;
    const send = () => {
        const msg = state.mode === 'focus' ? 'Focus session complete! Time for a break.' : 'Break over! Ready to focus?';
        new Notification('SmarTasker', { body: msg, icon: '/favicon.ico' });
    };
    if (Notification.permission === 'granted') send();
    else if (Notification.permission !== 'denied') Notification.requestPermission().then(p => { if (p === 'granted') send(); });
}

document.addEventListener('visibilitychange', () => {
    if (document.hidden) {
        elBgInd.style.display = state.isRunning ? 'flex' : 'none';
    } else {
        elBgInd.style.display = 'none';
        if (state.isRunning) {
            const remaining = secondsLeft();
            if (remaining <= 0) onComplete();
            else { elDisplay.textContent = fmt(remaining); startRaf(); }
        }
    }
});

function saveState() {
    if (!state.isRunning) return;
    localStorage.setItem(STORAGE_KEY, JSON.stringify({
        mode: state.mode, totalSeconds: state.totalSeconds,
        endTime: state.endTime, startedAt: state.startedAt, sessionCount: state.sessionCount,
    }));
}
function clearState() { localStorage.removeItem(STORAGE_KEY); }
function restoreState() {
    try {
        const saved = JSON.parse(localStorage.getItem(STORAGE_KEY));
        if (!saved || saved.endTime < Date.now()) { clearState(); return false; }
        state.mode = saved.mode; state.totalSeconds = saved.totalSeconds;
        state.endTime = saved.endTime; state.startedAt = saved.startedAt;
        state.sessionCount = saved.sessionCount; state.isRunning = true;
        elModeGrp.querySelectorAll('.mode-btn').forEach(b => {
            const isActive = b.dataset.mode === state.mode;
            b.style.background = isActive ? MODES[state.mode].color : 'transparent';
            b.style.color = isActive ? 'var(--c-bg)' : 'var(--c-muted2)';
        });
        elMode.textContent = MODES[state.mode].label;
        elBtnStart.innerHTML = '<i class="bi bi-pause-fill"></i> Pause';
        elBtnStart.style.background = 'linear-gradient(135deg,var(--c-amber),var(--c-coral))';
        elBgInd.style.display = 'flex';
        return true;
    } catch(e) { clearState(); return false; }
}

elBtnStart.addEventListener('click', startTimer);
elBtnReset.addEventListener('click', resetTimer);

elModeGrp.querySelectorAll('.mode-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        elModeGrp.querySelectorAll('.mode-btn').forEach(b => {
            b.style.background = 'transparent'; b.style.color = 'var(--c-muted2)';
        });
        btn.style.background = MODES[btn.dataset.mode].color;
        btn.style.color = 'var(--c-bg)';
        switchMode(btn.dataset.mode);
    });
});

document.getElementById('customMins').addEventListener('change', () => { if (!state.isRunning) resetTimer(); });
setInterval(() => { if (state.isRunning) saveState(); }, 5000);
renderDots();
if ('Notification' in window && Notification.permission === 'default') Notification.requestPermission();
if (!restoreState()) tick();
else startRaf();
</script>
@endpush
