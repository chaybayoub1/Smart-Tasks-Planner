<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Verify Code — {{ config('app.name', 'SmarTasker') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root { --primary: #6366f1; --primary-d: #4f46e5; --accent: #f59e0b; }

        html, body { height: 100%; font-family: 'Inter', sans-serif; }
        body { display: flex; align-items: center; justify-content: center; min-height: 100vh; overflow-y: auto; background: #1e1b4b; }

        /* Background — identical to forgot-password */
        .bg-scene { position: fixed; inset: 0; z-index: 0; pointer-events: none; background: radial-gradient(ellipse 80% 60% at 20% 50%, #2d1f6e 0%, #1e1b4b 55%, #0f0c2e 100%); overflow: hidden; }
        .bg-scene::before { content: ''; position: absolute; inset: 0; background-image: radial-gradient(circle at 15% 25%, rgba(99,102,241,.18) 0%, transparent 45%), radial-gradient(circle at 85% 75%, rgba(245,158,11,.10) 0%, transparent 40%), radial-gradient(circle at 60% 10%, rgba(139,92,246,.12) 0%, transparent 35%); }
        .bg-scene::after { content: ''; position: absolute; inset: 0; background-image: radial-gradient(rgba(255,255,255,.07) 1px, transparent 1px); background-size: 32px 32px; }
        .orb { position: absolute; border-radius: 50%; filter: blur(70px); opacity: .35; animation: drift 12s ease-in-out infinite alternate; }
        .orb-1 { width:380px; height:380px; background:#6366f1; top:-120px; left:-80px; animation-duration:14s; }
        .orb-2 { width:260px; height:260px; background:#8b5cf6; bottom:10%; right:5%; animation-duration:10s; animation-delay:-4s; }
        .orb-3 { width:180px; height:180px; background:#f59e0b; bottom:25%; left:10%; animation-duration:16s; animation-delay:-7s; opacity:.2; }
        @keyframes drift { from { transform: translate(0,0) scale(1); } to { transform: translate(30px,40px) scale(1.08); } }

        .otp-center {
            position: relative; z-index: 10; width: 100%; max-width: 460px;
            padding: 1.5rem 1.25rem; margin: auto;
            display: flex; flex-direction: column; align-items: center; gap: 1.5rem;
            animation: slideUp .5s cubic-bezier(.16,1,.3,1) both;
        }
        @keyframes slideUp { from { opacity:0; transform: translateY(24px); } to { opacity:1; transform: translateY(0); } }

        .otp-logo { font-family: 'Sora', sans-serif; font-size: 1.55rem; font-weight: 800; color: #fff; letter-spacing: -.03em; display: flex; align-items: center; gap: .3rem; text-decoration: none; user-select: none; }
        .otp-logo .dot { color: var(--accent); }

        .otp-card { width: 100%; background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.12); border-radius: 24px; padding: 2.5rem 2.25rem 2.25rem; backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px); box-shadow: 0 24px 60px rgba(0,0,0,.35), 0 1px 0 rgba(255,255,255,.08) inset; }

        .otp-header { text-align: center; margin-bottom: 2rem; }
        .otp-icon { width: 60px; height: 60px; border-radius: 16px; margin: 0 auto 1.2rem; background: linear-gradient(135deg, rgba(99,102,241,.32) 0%, rgba(79,70,229,.20) 100%); border: 1px solid rgba(99,102,241,.38); display: flex; align-items: center; justify-content: center; font-size: 1.55rem; color: #a5b4fc; box-shadow: 0 8px 28px rgba(99,102,241,.22); animation: iconPulse 3s ease-in-out infinite; }
        @keyframes iconPulse { 0%,100%{ box-shadow: 0 8px 28px rgba(99,102,241,.22); } 50%{ box-shadow: 0 8px 38px rgba(99,102,241,.46); } }
        .otp-header h2 { font-family: 'Sora', sans-serif; font-size: 1.65rem; font-weight: 700; color: #fff; letter-spacing: -.02em; margin-bottom: .45rem; }
        .otp-header p { color: rgba(255,255,255,.45); font-size: .875rem; line-height: 1.6; max-width: 300px; margin: 0 auto; }

        /* Email badge */
        .email-badge { display: inline-flex; align-items: center; gap: .4rem; background: rgba(99,102,241,.15); border: 1px solid rgba(99,102,241,.25); border-radius: 8px; padding: .3rem .75rem; font-size: .8rem; color: #a5b4fc; margin-top: .6rem; }

        /* Alert banners */
        .alert { border-radius: 10px; padding: .7rem 1rem; font-size: .82rem; line-height: 1.5; margin-bottom: 1.25rem; display: flex; align-items: flex-start; gap: .5rem; }
        .alert-success { background: rgba(99,102,241,.15); border: 1px solid rgba(99,102,241,.32); color: #a5b4fc; }
        .alert-error   { background: rgba(248,113,113,.12); border: 1px solid rgba(248,113,113,.3); color: #fca5a5; }
        .alert i { flex-shrink: 0; margin-top: .05rem; }

        /* ── 6-digit OTP boxes ── */
        .otp-label { display: block; font-size: .72rem; font-weight: 600; color: rgba(255,255,255,.5); margin-bottom: .75rem; letter-spacing: .06em; text-transform: uppercase; text-align: center; }

        .otp-boxes { display: flex; justify-content: center; gap: .6rem; margin-bottom: 1.5rem; }
        .otp-box {
            width: 52px; height: 60px;
            background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.15);
            border-radius: 12px; color: #fff; font-family: 'Sora', sans-serif;
            font-size: 1.5rem; font-weight: 700; text-align: center;
            outline: none; caret-color: #818cf8;
            transition: border-color .2s, background .2s, box-shadow .2s;
        }
        .otp-box:focus { border-color: rgba(99,102,241,.7); background: rgba(99,102,241,.12); box-shadow: 0 0 0 3px rgba(99,102,241,.18); }
        .otp-box.is-error { border-color: #f87171 !important; }
        .otp-box.is-filled { border-color: rgba(99,102,241,.45); background: rgba(99,102,241,.1); }

        /* Hidden aggregator input that carries the joined value to server */
        #otpHidden { display: none; }

        /* ── Buttons ── */
        .btn-primary-st { width: 100%; padding: 1rem 1.75rem; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; border-radius: 12px; color: #fff; font-family: 'Sora', sans-serif; font-size: 1rem; font-weight: 700; letter-spacing: .03em; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: .55rem; transition: transform .15s, box-shadow .15s, filter .15s; box-shadow: 0 6px 24px rgba(99,102,241,.45); }
        .btn-primary-st:hover { transform: translateY(-2px); box-shadow: 0 12px 32px rgba(99,102,241,.6); filter: brightness(1.09); }
        .btn-primary-st:active { transform: translateY(0); }
        .btn-primary-st:disabled { opacity: .5; cursor: not-allowed; transform: none; box-shadow: none; }

        .btn-spinner { display: none; width: 18px; height: 18px; border: 2px solid rgba(255,255,255,.3); border-top-color: #fff; border-radius: 50%; animation: spin .7s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .btn-primary-st.loading .btn-spinner { display: block; }
        .btn-primary-st.loading .btn-label { display: none; }

        /* ── Resend section ── */
        .resend-row { margin-top: 1.4rem; text-align: center; }
        .resend-hint { font-size: .8rem; color: rgba(255,255,255,.35); margin-bottom: .55rem; }

        #resendForm button {
            background: none; border: none; cursor: pointer; padding: 0;
            font-size: .82rem; color: rgba(255,255,255,.35);
            display: inline-flex; align-items: center; gap: .3rem;
            transition: color .18s;
        }
        #resendForm button:not(:disabled):hover { color: #a5b4fc; }
        #resendForm button:disabled { cursor: not-allowed; opacity: .5; }

        #countdown { font-size: .8rem; color: rgba(255,255,255,.4); }
        #countdown span { color: #a5b4fc; font-weight: 600; }

        /* ── Back link ── */
        .fp-back { display: inline-flex; align-items: center; gap: .4rem; font-size: .82rem; color: rgba(255,255,255,.35); text-decoration: none; transition: color .18s, gap .18s; }
        .fp-back i { font-size: .8rem; transition: transform .18s; }
        .fp-back:hover { color: #a5b4fc; gap: .6rem; }
        .fp-back:hover i { transform: translateX(-3px); }

        /* Shake animation for wrong OTP */
        @keyframes shake { 0%,100%{transform:translateX(0)} 20%{transform:translateX(-6px)} 40%{transform:translateX(6px)} 60%{transform:translateX(-4px)} 80%{transform:translateX(4px)} }
        .shake { animation: shake .4s ease; }
    </style>
</head>
<body>

<div class="bg-scene">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
</div>

<div class="otp-center">

    <a href="{{ url('/') }}" class="otp-logo">
        🎓 Smar<span class="dot">Tasker</span>
    </a>

    <div class="otp-card">

        <div class="otp-header">
            <div class="otp-icon">
                <i class="bi bi-envelope-check-fill"></i>
            </div>
            <h2>Check your inbox</h2>
            <p>We sent a 6-digit code to</p>
            @if(session('otp_email'))
                <div class="email-badge">
                    <i class="bi bi-envelope-fill" style="font-size:.75rem;"></i>
                    {{ session('otp_email') }}
                </div>
            @endif
        </div>

        {{-- Success message (e.g. after resend) --}}
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('status') }}
            </div>
        @endif

        {{-- Validation errors --}}
        @if ($errors->any())
            <div class="alert alert-error" role="alert" id="errorAlert">
                <i class="bi bi-exclamation-circle-fill"></i>
                {{ $errors->first() }}
            </div>
        @endif

        {{-- OTP entry form --}}
        <form method="POST" action="{{ route('password.verify-otp.store') }}" id="otpForm">
            @csrf

            <label class="otp-label" for="otp-1">Enter verification code</label>

            <div class="otp-boxes" id="otpBoxes">
                @for ($i = 1; $i <= 6; $i++)
                    <input
                        class="otp-box {{ $errors->has('otp') ? 'is-error' : '' }}"
                        type="text"
                        inputmode="numeric"
                        maxlength="1"
                        id="otp-{{ $i }}"
                        autocomplete="off"
                        pattern="[0-9]"
                    >
                @endfor
            </div>

            {{-- Hidden input carries the joined 6-digit value --}}
            <input type="hidden" name="otp" id="otpHidden">

            <button type="submit" class="btn-primary-st" id="verifyBtn" disabled>
                <span class="btn-spinner"></span>
                <span class="btn-label" style="display:flex;align-items:center;gap:.55rem;">
                    <i class="bi bi-shield-check-fill"></i>
                    Verify Code
                </span>
            </button>
        </form>

        {{-- Resend section --}}
        <div class="resend-row">
            <p class="resend-hint">Didn't receive it? Check your spam folder or</p>
            <div id="countdown">Resend available in <span id="timer">60</span>s</div>
            <form method="POST" action="{{ route('password.verify-otp.resend') }}" id="resendForm" style="display:none;">
                @csrf
                <button type="submit" id="resendBtn">
                    <i class="bi bi-arrow-clockwise"></i>
                    Resend code
                </button>
            </form>
        </div>

    </div>

    <a href="{{ route('password.request') }}" class="fp-back">
        <i class="bi bi-arrow-left"></i>
        Change email
    </a>

</div>

<script>
(function () {
    const boxes    = Array.from(document.querySelectorAll('.otp-box'));
    const hidden   = document.getElementById('otpHidden');
    const form     = document.getElementById('otpForm');
    const verifyBtn = document.getElementById('verifyBtn');
    const otpBoxes  = document.getElementById('otpBoxes');

    // Pre-fill with old value if server sent it back after error
    @if(old('otp'))
    const oldVal = '{{ old('otp') }}';
    boxes.forEach((b, i) => {
        if (oldVal[i]) { b.value = oldVal[i]; b.classList.add('is-filled'); }
    });
    syncHidden();
    @endif

    function syncHidden() {
        const val = boxes.map(b => b.value).join('');
        hidden.value = val;
        verifyBtn.disabled = val.length < 6;
        boxes.forEach(b => {
            b.classList.toggle('is-filled', b.value.length === 1);
        });
    }

    boxes.forEach((box, idx) => {
        box.addEventListener('keydown', e => {
            if (e.key === 'Backspace' && !box.value && idx > 0) {
                boxes[idx - 1].focus();
                boxes[idx - 1].value = '';
                syncHidden();
                e.preventDefault();
            }
        });

        box.addEventListener('input', e => {
            // Allow only digits
            box.value = box.value.replace(/\D/g, '').slice(-1);
            syncHidden();
            if (box.value && idx < 5) boxes[idx + 1].focus();
        });

        box.addEventListener('paste', e => {
            e.preventDefault();
            const text = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
            text.split('').slice(0, 6).forEach((ch, i) => {
                if (boxes[i]) boxes[i].value = ch;
            });
            syncHidden();
            const next = Math.min(text.length, 5);
            boxes[next].focus();
        });
    });

    form.addEventListener('submit', function () {
        syncHidden();
        if (hidden.value.length < 6) return false;
        verifyBtn.classList.add('loading');
        verifyBtn.disabled = true;
    });

    // Shake boxes if there were errors
    @if($errors->has('otp'))
    otpBoxes.classList.add('shake');
    setTimeout(() => otpBoxes.classList.remove('shake'), 500);
    // Clear boxes so user can re-enter
    boxes.forEach(b => { b.value = ''; b.classList.remove('is-filled', 'is-error'); });
    hidden.value = '';
    verifyBtn.disabled = true;
    boxes[0].focus();
    @else
    boxes[0].focus();
    @endif

    // ── Resend countdown ──────────────────────────────────────
    let seconds = 60;
    const countdown  = document.getElementById('countdown');
    const timerSpan  = document.getElementById('timer');
    const resendForm = document.getElementById('resendForm');

    const tick = setInterval(() => {
        seconds--;
        timerSpan.textContent = seconds;
        if (seconds <= 0) {
            clearInterval(tick);
            countdown.style.display  = 'none';
            resendForm.style.display = 'block';
        }
    }, 1000);

    resendForm.addEventListener('submit', function () {
        document.getElementById('resendBtn').disabled = true;
    });
})();
</script>

</body>
</html>
