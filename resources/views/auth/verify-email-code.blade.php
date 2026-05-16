<x-guest-layout>

{{-- ══════════════════════════════════════════════════════════
     VERIFY EMAIL — CODE ENTRY PAGE  (redesigned)
     Matches the dark-glass design system from login / register.
     • Single-scrollbar strategy (identical to login/register)
     • Centred card, no internal scroll
     • Polished OTP boxes with autofocus / backspace / paste
     • Animated countdown timer + resend cooldown
══════════════════════════════════════════════════════════ --}}
<style>

    /* ══════════════════════════════════════════════════════════
       1. SINGLE-SCROLLBAR FIX — mirrors login / register
    ══════════════════════════════════════════════════════════ */

    html {
        height: auto !important;
        overflow-y: scroll !important;
        overflow-x: hidden !important;
        scrollbar-width: none !important;
        -ms-overflow-style: none !important;
    }
    html::-webkit-scrollbar { display: none !important; }

    body {
        height: auto !important;
        min-height: 100vh !important;
        overflow: visible !important;
    }

    .auth-wrapper {
        min-height: 100vh !important;
        height: auto !important;
        align-items: stretch !important;
    }

    /* Left panel — sticky viewport column */
    .panel-left {
        position: sticky !important;
        top: 0 !important;
        height: 100vh !important;
        overflow-x: clip !important;
        overflow-y: visible !important;
        justify-content: flex-start !important;
        padding-top: 2.5rem !important;
        align-self: flex-start !important;
    }

    /* Right panel — no internal scroll, content-height only */
    .panel-right {
        width: 100% !important;
        height: auto !important;
        min-height: 100vh !important;
        max-height: none !important;
        overflow: clip !important;
        align-items: center !important;     /* ← centred vertically */
        justify-content: center !important;
        align-self: stretch !important;
        padding: 2.5rem 1.5rem 3rem !important;
        display: flex !important;
        flex-direction: column !important;
    }
    @media (min-width: 1024px) {
        .panel-right {
            width: 520px !important;
            flex-shrink: 0 !important;
            overflow: clip !important;
            max-height: none !important;
            align-items: center !important;
            padding: 2.5rem 2rem 3rem !important;
        }
    }

    /* form-card wrapper */
    .form-card {
        width: 100% !important;
        max-width: 460px !important;
        height: auto !important;
        margin: auto !important;            /* vertical centering within flex col */
    }

    /* card-glass — grows with content, never clips */
    .card-glass {
        height: auto !important;
        min-height: unset !important;
        max-height: none !important;
        overflow: visible !important;
        padding: 2rem 1.75rem 2.25rem !important;
    }

    @media (max-width: 1023px) {
        .panel-right { padding: 2rem 1.5rem 3rem !important; }
        .form-card   { max-width: 480px !important; }
    }
    @media (max-width: 640px) {
        .panel-right { padding: 1.25rem .75rem 2.5rem !important; }
        .form-card   { max-width: 100% !important; }
        .card-glass  { padding: 1.5rem 1.25rem 1.75rem !important; border-radius: 14px !important; }
    }


    /* ══════════════════════════════════════════════════════════
       2. VERIFY-PAGE SPECIFIC STYLES
    ══════════════════════════════════════════════════════════ */

    /* ── Icon badge ─────────────────────────────────────────── */
    .verify-icon-badge {
        width: 68px; height: 68px;
        background: linear-gradient(135deg, rgba(99,102,241,.28), rgba(79,70,229,.16));
        border: 1.5px solid rgba(99,102,241,.38);
        border-radius: 20px;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 1.35rem;
        font-size: 1.9rem;
        box-shadow: 0 8px 28px rgba(99,102,241,.22);
        animation: badgePop .55s cubic-bezier(.34,1.56,.64,1) both;
    }
    @keyframes badgePop {
        0%   { opacity:0; transform: scale(.6) translateY(6px); }
        100% { opacity:1; transform: scale(1) translateY(0); }
    }

    /* ── Step dots ──────────────────────────────────────────── */
    .step-dots {
        display: flex; gap: .45rem; justify-content: center;
        margin-bottom: 1.65rem;
    }
    .step-dot {
        height: 4px; border-radius: 99px;
        background: rgba(255,255,255,.1);
        transition: background .35s, width .35s;
    }
    .step-dot.done   { width: 24px; background: rgba(99,102,241,.45); }
    .step-dot.active { width: 32px; background: #6366f1; }
    .step-dot.next   { width: 24px; }

    /* ── Headings ───────────────────────────────────────────── */
    .verify-title {
        font-family: 'Sora', sans-serif;
        font-size: 1.5rem; font-weight: 800;
        color: #fff; text-align: center;
        letter-spacing: -.022em; margin-bottom: .5rem;
    }
    .verify-title span { color: #818cf8; }

    .verify-subtitle {
        font-size: .85rem;
        color: rgba(255,255,255,.42);
        line-height: 1.65; text-align: center;
        margin-bottom: 1.6rem;
    }
    .verify-subtitle strong {
        color: rgba(255,255,255,.72);
        font-weight: 600;
    }

    /* ── Divider ────────────────────────────────────────────── */
    .verify-divider {
        height: 1px;
        background: rgba(255,255,255,.07);
        margin: 1.25rem 0;
    }

    /* ── Alert boxes ────────────────────────────────────────── */
    .alert-success, .alert-error {
        border-radius: 10px;
        padding: .8rem 1rem;
        font-size: .83rem;
        display: flex; align-items: flex-start; gap: .5rem;
        margin-bottom: 1.2rem; text-align: left;
        animation: alertIn .3s ease both;
    }
    @keyframes alertIn {
        from { opacity:0; transform: translateY(-6px); }
        to   { opacity:1; transform: translateY(0); }
    }
    .alert-success {
        background: rgba(34,197,94,.1);
        border: 1px solid rgba(34,197,94,.3);
        color: #86efac;
    }
    .alert-error {
        background: rgba(239,68,68,.1);
        border: 1px solid rgba(239,68,68,.28);
        color: #fca5a5;
    }
    .alert-success i, .alert-error i { flex-shrink:0; margin-top:1px; }

    /* ── OTP box row ────────────────────────────────────────── */
    .code-inputs-wrapper {
        display: flex;
        gap: .4rem;
        justify-content: center;
        margin-bottom: 1.1rem;
    }
    /* gap between first 4 and last 4 (visual grouping) */
    .code-box:nth-child(4) { margin-right: .5rem; }

    .code-box {
        width: 42px; height: 52px;
        background: rgba(255,255,255,.06);
        border: 1.5px solid rgba(255,255,255,.13);
        border-radius: 11px;
        text-align: center;
        font-family: 'Courier New', 'Consolas', monospace;
        font-size: 1.25rem; font-weight: 800;
        color: #c7d2fe;
        caret-color: #818cf8;
        outline: none;
        -moz-appearance: textfield;
        transition: border-color .18s ease, background .18s ease, box-shadow .18s ease, transform .12s ease;
    }
    .code-box::-webkit-outer-spin-button,
    .code-box::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    .code-box::selection { background: rgba(99,102,241,.4); }

    .code-box:focus {
        border-color: rgba(99,102,241,.8);
        background: rgba(99,102,241,.12);
        box-shadow: 0 0 0 3px rgba(99,102,241,.18);
        transform: translateY(-2px) scale(1.04);
    }
    .code-box.filled {
        border-color: rgba(99,102,241,.55);
        background: rgba(99,102,241,.09);
        color: #e0e7ff;
    }
    .code-box.error-box {
        border-color: rgba(239,68,68,.65) !important;
        background: rgba(239,68,68,.08) !important;
        animation: shakeBox .38s ease;
    }
    @keyframes shakeBox {
        0%,100% { transform: translateX(0); }
        20%      { transform: translateX(-5px); }
        40%      { transform: translateX(5px); }
        60%      { transform: translateX(-4px); }
        80%      { transform: translateX(3px); }
    }

    /* Hidden real input */
    #codeInput { display: none; }

    /* ── Timer pill ─────────────────────────────────────────── */
    .timer-pill {
        display: inline-flex; align-items: center; gap: .45rem;
        background: rgba(251,191,36,.07);
        border: 1px solid rgba(251,191,36,.2);
        border-radius: 99px;
        padding: .35rem .85rem;
        font-size: .78rem;
        color: rgba(255,255,255,.42);
        margin-bottom: 1.25rem;
    }
    .timer-pill i { color: #fbbf24; font-size: .8rem; }
    #countdownTimer {
        color: #fbbf24;
        font-weight: 700;
        font-variant-numeric: tabular-nums;
        letter-spacing: .04em;
        transition: color .3s;
    }
    #countdownTimer.urgent {
        color: #ef4444 !important;
        animation: timerPulse 1s infinite;
    }
    @keyframes timerPulse {
        0%,100% { opacity: 1; }
        50%      { opacity: .45; }
    }
    .timer-pill.expired {
        background: rgba(239,68,68,.08);
        border-color: rgba(239,68,68,.22);
    }

    /* ── Submit button ──────────────────────────────────────── */
    .btn-verify {
        width: 100%; padding: .88rem 1.5rem;
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        border: none; border-radius: 11px;
        color: #fff; font-family: 'Sora', sans-serif;
        font-size: .96rem; font-weight: 700; letter-spacing: .02em;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: .55rem;
        transition: transform .15s ease, box-shadow .15s ease, filter .15s ease, opacity .15s;
        box-shadow: 0 4px 22px rgba(99,102,241,.42);
        margin-bottom: 0;
    }
    .btn-verify:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(99,102,241,.58);
        filter: brightness(1.07);
    }
    .btn-verify:active:not(:disabled) { transform: translateY(0); }
    .btn-verify:disabled {
        opacity: .38; cursor: not-allowed;
        transform: none !important; box-shadow: none !important; filter: none !important;
    }

    /* ── Resend row ─────────────────────────────────────────── */
    .resend-row {
        text-align: center;
        font-size: .83rem;
        color: rgba(255,255,255,.36);
    }
    .resend-btn {
        background: none; border: none; padding: 0;
        color: #818cf8; font-size: .83rem; font-weight: 600;
        cursor: pointer;
        text-underline-offset: 3px;
        text-decoration: underline;
        text-decoration-color: rgba(129,140,248,.4);
        transition: color .15s ease, text-decoration-color .15s ease;
    }
    .resend-btn:hover:not(:disabled) {
        color: #a5b4fc;
        text-decoration-color: rgba(165,180,252,.6);
    }
    .resend-btn:disabled {
        color: rgba(255,255,255,.22) !important;
        cursor: not-allowed;
        text-decoration: none;
    }

    /* Cooldown badge inside resend */
    .resend-countdown {
        display: inline-flex; align-items: center; gap: .3rem;
        background: rgba(99,102,241,.1);
        border: 1px solid rgba(99,102,241,.2);
        border-radius: 99px;
        padding: .15rem .55rem;
        font-size: .75rem; font-weight: 600;
        color: #818cf8;
        margin-left: .25rem;
        vertical-align: middle;
    }

    /* ── Back link ──────────────────────────────────────────── */
    .back-link {
        display: inline-flex; align-items: center; gap: .35rem;
        font-size: .78rem; color: rgba(255,255,255,.28);
        text-decoration: none;
        transition: color .15s;
    }
    .back-link:hover { color: rgba(255,255,255,.55); }

    /* ── Mobile OTP box size ────────────────────────────────── */
    @media (max-width: 420px) {
        .code-box { width: 36px; height: 46px; font-size: 1.1rem; border-radius: 9px; }
        .code-inputs-wrapper { gap: .3rem; }
        .code-box:nth-child(4) { margin-right: .35rem; }
    }

</style>


{{-- Mobile brand mark --}}
<div class="brand-mobile">
    🎓 Smar<span class="dot">Tasker</span>
</div>

<div class="verify-card" style="width:100%;max-width:440px;margin:0 auto;text-align:center;">

    {{-- Step indicator: ① Register → ② Verify → ③ Dashboard --}}
    <div class="step-dots" aria-label="Step 2 of 3: Email verification">
        <div class="step-dot done"   title="Account created"></div>
        <div class="step-dot active" title="Email verification (current)"></div>
        <div class="step-dot next"   title="Dashboard"></div>
    </div>

    {{-- Icon badge --}}
    <div class="verify-icon-badge" aria-hidden="true">📬</div>

    {{-- Heading --}}
    <h1 class="verify-title">Check your <span>inbox</span></h1>

    <p class="verify-subtitle">
        We sent an 8-character code to<br>
        <strong>{{ Auth::user()->email }}</strong><br>
        Enter it below to activate your account.
    </p>

    {{-- ── Success flash (resend) ───────────────────────────────── --}}
    @if (session('status') === 'verification-link-sent')
        <div class="alert-success" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            <span>A fresh code has been sent to your email.</span>
        </div>
    @endif

    {{-- ── Validation errors ──────────────────────────────────────── --}}
    @if ($errors->has('code'))
        <div class="alert-error" id="codeErrorAlert" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span>{{ $errors->first('code') }}</span>
        </div>
    @endif

    @if ($errors->has('resend'))
        <div class="alert-error" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span>{{ $errors->first('resend') }}</span>
        </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════
         CODE ENTRY FORM
    ══════════════════════════════════════════════════════════ --}}
    <form method="POST" action="{{ route('verification.code.verify') }}" id="verifyForm" novalidate>
        @csrf

        {{-- Hidden input that carries the assembled code --}}
        <input type="hidden" name="code" id="codeInput">

        {{-- 8 OTP boxes --}}
        <div class="code-inputs-wrapper" id="codeBoxes" role="group" aria-label="8-character verification code">
            @for ($i = 0; $i < 8; $i++)
                <input
                    type="text"
                    inputmode="text"
                    maxlength="1"
                    class="code-box {{ $errors->has('code') ? 'error-box' : '' }}"
                    data-index="{{ $i }}"
                    autocomplete="off"
                    autocorrect="off"
                    autocapitalize="characters"
                    spellcheck="false"
                    aria-label="Character {{ $i + 1 }} of 8"
                >
            @endfor
        </div>

        {{-- Countdown timer pill --}}
        <div style="display:flex;justify-content:center;margin-bottom:1.25rem;">
            <div class="timer-pill" id="timerPill">
                <i class="bi bi-clock" id="timerIcon"></i>
                <span>Code expires in</span>
                <span id="countdownTimer">10:00</span>
            </div>
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn-verify" id="verifyBtn" disabled aria-disabled="true">
            <i class="bi bi-shield-check-fill"></i>
            Verify Email Address
        </button>
    </form>

    <div class="verify-divider"></div>

    {{-- ══════════════════════════════════════════════════════════
         RESEND FORM
    ══════════════════════════════════════════════════════════ --}}
    <div class="resend-row" style="margin-bottom:.85rem;">
        Didn't receive a code?&nbsp;
        <form method="POST" action="{{ route('verification.code.resend') }}" style="display:inline;" id="resendForm">
            @csrf
            <button type="submit" class="resend-btn" id="resendBtn">Resend code</button>
        </form>
        <span id="resendCooldown" class="resend-countdown" style="display:none;">
            <i class="bi bi-hourglass-split" style="font-size:.7rem;"></i>
            <span id="resendCountText">30s</span>
        </span>
    </div>

    {{-- Use a different account --}}
    <div>
        <form method="POST" action="{{ route('logout') }}" style="display:inline;" id="logoutForm">
            @csrf
            <a href="#" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();" class="back-link">
                <i class="bi bi-arrow-left"></i>
                Use a different account
            </a>
        </form>
    </div>

    {{-- Footer --}}
    <div style="text-align:center; margin-top:1.5rem;">
        <span style="font-size:.7rem; color:rgba(255,255,255,.18);">🎓 SmarTasker &copy; {{ date('Y') }}</span>
    </div>

</div>


{{-- ══════════════════════════════════════════════════════════
     JAVASCRIPT — OTP navigation · paste · countdown · resend
══════════════════════════════════════════════════════════ --}}
<script>
(function () {
    'use strict';

    /* ── DOM refs ─────────────────────────────────────────── */
    const boxes       = Array.from(document.querySelectorAll('.code-box'));
    const hiddenInput = document.getElementById('codeInput');
    const verifyBtn   = document.getElementById('verifyBtn');
    const timerEl     = document.getElementById('countdownTimer');
    const timerPill   = document.getElementById('timerPill');
    const timerIcon   = document.getElementById('timerIcon');
    const resendBtn   = document.getElementById('resendBtn');
    const resendForm  = document.getElementById('resendForm');
    const cooldownBadge = document.getElementById('resendCooldown');
    const cooldownText  = document.getElementById('resendCountText');

    /* ── Sync all boxes → hidden input + button state ──────── */
    function syncCode() {
        const val = boxes.map(b => b.value.toUpperCase()).join('');
        hiddenInput.value = val;
        const complete = val.length === 8;
        verifyBtn.disabled = !complete;
        verifyBtn.setAttribute('aria-disabled', complete ? 'false' : 'true');
        boxes.forEach(b => {
            const has = b.value.length === 1;
            b.classList.toggle('filled', has);
            if (has) b.classList.remove('error-box');
        });
    }

    /* ── Jump to next unfilled box ─────────────────────────── */
    function focusNext(idx) {
        for (let i = idx + 1; i < 8; i++) {
            if (!boxes[i].value) { boxes[i].focus(); return; }
        }
        boxes[7].focus(); // all filled — rest on last
    }

    /* ── Per-box event handlers ────────────────────────────── */
    boxes.forEach(function (box, idx) {

        box.addEventListener('focus', function () { box.select(); });

        box.addEventListener('input', function () {
            // keep only the last character, uppercased
            const ch = box.value.replace(/\s/g, '').slice(-1).toUpperCase();
            box.value = ch;
            syncCode();
            if (ch) focusNext(idx);
        });

        box.addEventListener('keydown', function (e) {
            switch (e.key) {
                case 'Backspace':
                    e.preventDefault();
                    if (box.value) {
                        box.value = '';
                        syncCode();
                    } else if (idx > 0) {
                        boxes[idx - 1].value = '';
                        boxes[idx - 1].focus();
                        syncCode();
                    }
                    break;

                case 'Delete':
                    e.preventDefault();
                    box.value = '';
                    syncCode();
                    break;

                case 'ArrowLeft':
                    if (idx > 0) { boxes[idx - 1].focus(); e.preventDefault(); }
                    break;

                case 'ArrowRight':
                    if (idx < 7) { boxes[idx + 1].focus(); e.preventDefault(); }
                    break;

                case 'Enter':
                    if (!verifyBtn.disabled) document.getElementById('verifyForm').submit();
                    e.preventDefault();
                    break;
            }
        });

        // Handle direct click into a filled box — allow replace
        box.addEventListener('click', function () { box.select(); });
    });

    /* ── Paste handler — fills boxes from any position ─────── */
    document.addEventListener('paste', function (e) {
        e.preventDefault();
        const text = (e.clipboardData || window.clipboardData)
            .getData('text')
            .replace(/\s/g, '')
            .toUpperCase()
            .slice(0, 8);
        if (!text.length) return;

        // Find focused box index, default 0
        const focusedIdx = boxes.findIndex(b => b === document.activeElement);
        const start = focusedIdx >= 0 ? focusedIdx : 0;

        text.split('').forEach(function (ch, i) {
            if (boxes[start + i]) boxes[start + i].value = ch;
        });

        // Focus the box right after the pasted block
        const afterPaste = Math.min(start + text.length, 7);
        boxes[afterPaste].focus();
        syncCode();
    });

    /* ── Auto-focus first empty box on load ────────────────── */
    const firstEmpty = boxes.findIndex(b => !b.value);
    (firstEmpty >= 0 ? boxes[firstEmpty] : boxes[0]).focus();

    /* ── Countdown timer (10 min = 600 s) ──────────────────── */
    let seconds = 600;

    function renderTimer() {
        const m = Math.floor(seconds / 60);
        const s = seconds % 60;
        timerEl.textContent = m + ':' + String(s).padStart(2, '0');

        if (seconds <= 60 && seconds > 0) {
            timerEl.classList.add('urgent');
            timerPill.style.background = 'rgba(239,68,68,.07)';
            timerPill.style.borderColor = 'rgba(239,68,68,.22)';
            timerIcon.style.color = '#ef4444';
        }

        if (seconds <= 0) {
            timerEl.textContent = 'Expired';
            timerEl.style.color = '#ef4444';
            timerEl.classList.remove('urgent');
            timerPill.classList.add('expired');
            timerIcon.className = 'bi bi-x-circle';
            timerIcon.style.color = '#ef4444';
            verifyBtn.disabled = true;
            verifyBtn.innerHTML = '<i class="bi bi-x-circle-fill"></i> Code expired — please resend';
            clearInterval(timerInterval);
            return;
        }
        seconds--;
    }

    renderTimer();
    const timerInterval = setInterval(renderTimer, 1000);

    /* ── Resend — 30-second cooldown visual lock ────────────── */
    resendForm.addEventListener('submit', function () {
        resendBtn.disabled = true;
        cooldownBadge.style.display = 'inline-flex';
        let cool = 30;
        cooldownText.textContent = cool + 's';

        const cd = setInterval(function () {
            cool--;
            if (cool <= 0) {
                clearInterval(cd);
                resendBtn.disabled = false;
                cooldownBadge.style.display = 'none';
            } else {
                cooldownText.textContent = cool + 's';
            }
        }, 1000);
    });

})();
</script>

{{-- ══════════════════════════════════════════════════════════
     LAYOUT: right panel uses flex column + justify-content:center
     so the verify card floats in the middle of the viewport.
     Because the card is short, there will never be scroll.
══════════════════════════════════════════════════════════ --}}

</x-guest-layout>