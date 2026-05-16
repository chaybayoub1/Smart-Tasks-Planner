<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Reset Password — {{ config('app.name', 'SmarTasker') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root { --primary: #6366f1; --primary-d: #4f46e5; --accent: #f59e0b; }

        html, body { height: 100%; font-family: 'Inter', sans-serif; }
        body { display: flex; align-items: center; justify-content: center; min-height: 100vh; overflow-y: auto; background: #1e1b4b; }

        .bg-scene { position: fixed; inset: 0; z-index: 0; pointer-events: none; background: radial-gradient(ellipse 80% 60% at 20% 50%, #2d1f6e 0%, #1e1b4b 55%, #0f0c2e 100%); overflow: hidden; }
        .bg-scene::before { content: ''; position: absolute; inset: 0; background-image: radial-gradient(circle at 15% 25%, rgba(99,102,241,.18) 0%, transparent 45%), radial-gradient(circle at 85% 75%, rgba(245,158,11,.10) 0%, transparent 40%), radial-gradient(circle at 60% 10%, rgba(139,92,246,.12) 0%, transparent 35%); }
        .bg-scene::after { content: ''; position: absolute; inset: 0; background-image: radial-gradient(rgba(255,255,255,.07) 1px, transparent 1px); background-size: 32px 32px; }
        .orb { position: absolute; border-radius: 50%; filter: blur(70px); opacity: .35; animation: drift 12s ease-in-out infinite alternate; }
        .orb-1 { width:380px; height:380px; background:#6366f1; top:-120px; left:-80px; animation-duration:14s; }
        .orb-2 { width:260px; height:260px; background:#8b5cf6; bottom:10%; right:5%; animation-duration:10s; animation-delay:-4s; }
        .orb-3 { width:180px; height:180px; background:#f59e0b; bottom:25%; left:10%; animation-duration:16s; animation-delay:-7s; opacity:.2; }
        @keyframes drift { from { transform: translate(0,0) scale(1); } to { transform: translate(30px,40px) scale(1.08); } }

        .rp-center { position: relative; z-index: 10; width: 100%; max-width: 460px; padding: 1.5rem 1.25rem; margin: auto; display: flex; flex-direction: column; align-items: center; gap: 1.5rem; animation: slideUp .5s cubic-bezier(.16,1,.3,1) both; }
        @keyframes slideUp { from { opacity:0; transform: translateY(24px); } to { opacity:1; transform: translateY(0); } }

        .rp-logo { font-family: 'Sora', sans-serif; font-size: 1.55rem; font-weight: 800; color: #fff; letter-spacing: -.03em; display: flex; align-items: center; gap: .3rem; text-decoration: none; user-select: none; }
        .rp-logo .dot { color: var(--accent); }

        .rp-card { width: 100%; background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.12); border-radius: 24px; padding: 2.5rem 2.25rem 2.25rem; backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px); box-shadow: 0 24px 60px rgba(0,0,0,.35), 0 1px 0 rgba(255,255,255,.08) inset; }

        .rp-header { text-align: center; margin-bottom: 2rem; }
        .rp-icon { width: 60px; height: 60px; border-radius: 16px; margin: 0 auto 1.2rem; background: linear-gradient(135deg, rgba(99,102,241,.32) 0%, rgba(79,70,229,.20) 100%); border: 1px solid rgba(99,102,241,.38); display: flex; align-items: center; justify-content: center; font-size: 1.55rem; color: #a5b4fc; box-shadow: 0 8px 28px rgba(99,102,241,.22); animation: iconPulse 3s ease-in-out infinite; }
        @keyframes iconPulse { 0%,100%{ box-shadow: 0 8px 28px rgba(99,102,241,.22); } 50%{ box-shadow: 0 8px 38px rgba(99,102,241,.46); } }
        .rp-header h2 { font-family: 'Sora', sans-serif; font-size: 1.65rem; font-weight: 700; color: #fff; letter-spacing: -.02em; margin-bottom: .45rem; }
        .rp-header p { color: rgba(255,255,255,.45); font-size: .875rem; line-height: 1.6; max-width: 300px; margin: 0 auto; }

        .alert { border-radius: 10px; padding: .7rem 1rem; font-size: .82rem; line-height: 1.5; margin-bottom: 1.25rem; display: flex; align-items: flex-start; gap: .5rem; }
        .alert-error { background: rgba(248,113,113,.12); border: 1px solid rgba(248,113,113,.3); color: #fca5a5; }
        .alert i { flex-shrink: 0; margin-top: .05rem; }

        .input-group-st { position: relative; margin-bottom: 1.25rem; }
        .input-group-st label { display: block; font-size: .72rem; font-weight: 600; color: rgba(255,255,255,.5); margin-bottom: .35rem; letter-spacing: .06em; text-transform: uppercase; }
        .input-wrap { position: relative; display: flex; align-items: center; }
        .input-icon-left { position: absolute; left: .9rem; top: 50%; transform: translateY(-50%); color: rgba(255,255,255,.35); font-size: 1rem; pointer-events: none; transition: color .2s; z-index: 2; }
        .input-wrap:focus-within .input-icon-left { color: #818cf8; }
        .input-eye { position: absolute; right: .9rem; top: 50%; transform: translateY(-50%); background: none; border: none; color: rgba(255,255,255,.3); cursor: pointer; font-size: 1rem; transition: color .2s; z-index: 2; padding: 0; }
        .input-eye:hover { color: #818cf8; }

        .input-group-st input[type="password"],
        .input-group-st input[type="text"],
        .input-group-st input[type="email"] {
            width: 100%; padding: .95rem 2.8rem .95rem 2.8rem; background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.12); border-radius: 12px; color: #fff; font-size: .95rem; font-family: 'Inter', sans-serif; outline: none; transition: border-color .2s, background .2s, box-shadow .2s;
        }
        .input-group-st input::placeholder { color: rgba(255,255,255,.25); }
        .input-wrap:focus-within input { border-color: rgba(99,102,241,.7); background: rgba(99,102,241,.1); box-shadow: 0 0 0 3px rgba(99,102,241,.15); }
        .input-group-st input.is-error { border-color: #f87171 !important; }
        .err-text { display: block; margin-top: .3rem; font-size: .78rem; color: #fca5a5; }

        /* Password strength bar */
        .strength-bar { margin-top: .5rem; height: 4px; border-radius: 4px; background: rgba(255,255,255,.1); overflow: hidden; }
        .strength-fill { height: 100%; border-radius: 4px; width: 0; transition: width .3s, background .3s; }

        .btn-primary-st { width: 100%; padding: 1rem 1.75rem; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none; border-radius: 12px; color: #fff; font-family: 'Sora', sans-serif; font-size: 1rem; font-weight: 700; letter-spacing: .03em; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: .55rem; transition: transform .15s, box-shadow .15s, filter .15s; box-shadow: 0 6px 24px rgba(99,102,241,.45); }
        .btn-primary-st:hover { transform: translateY(-2px); box-shadow: 0 12px 32px rgba(99,102,241,.6); filter: brightness(1.09); }
        .btn-primary-st:active { transform: translateY(0); }
        .btn-primary-st:disabled { opacity: .5; cursor: not-allowed; transform: none; }

        .btn-spinner { display: none; width: 18px; height: 18px; border: 2px solid rgba(255,255,255,.3); border-top-color: #fff; border-radius: 50%; animation: spin .7s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .btn-primary-st.loading .btn-spinner { display: block; }
        .btn-primary-st.loading .btn-label { display: none; }
    </style>
</head>
<body>

<div class="bg-scene">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
</div>

<div class="rp-center">

    <a href="{{ url('/') }}" class="rp-logo">
        🎓 Smar<span class="dot">Tasker</span>
    </a>

    <div class="rp-card">

        <div class="rp-header">
            <div class="rp-icon">
                <i class="bi bi-key-fill"></i>
            </div>
            <h2>Set new password</h2>
            <p>Choose a strong password for your account.</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-error" role="alert">
                <i class="bi bi-exclamation-circle-fill"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.store') }}" id="rpForm">
            @csrf

            {{-- Session token carried as hidden field --}}
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            {{-- Email (pre-filled from session) --}}
            <div class="input-group-st">
                <label for="email">Email address</label>
                <div class="input-wrap">
                    <span class="input-icon-left"><i class="bi bi-envelope-fill"></i></span>
                    <input
                        id="email" type="email" name="email"
                        value="{{ old('email', session('otp_email')) }}"
                        placeholder="you@university.edu" required autocomplete="username"
                        class="{{ $errors->get('email') ? 'is-error' : '' }}"
                    >
                </div>
                @if ($errors->get('email'))
                    <span class="err-text">{{ $errors->first('email') }}</span>
                @endif
            </div>

            {{-- New password --}}
            <div class="input-group-st">
                <label for="password">New password</label>
                <div class="input-wrap">
                    <span class="input-icon-left"><i class="bi bi-lock-fill"></i></span>
                    <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Min. 8 characters" class="{{ $errors->get('password') ? 'is-error' : '' }}">
                    <button type="button" class="input-eye" onclick="toggleVis('password', this)"><i class="bi bi-eye-fill"></i></button>
                </div>
                <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
                @if ($errors->get('password'))
                    <span class="err-text">{{ $errors->first('password') }}</span>
                @endif
            </div>

            {{-- Confirm password --}}
            <div class="input-group-st">
                <label for="password_confirmation">Confirm new password</label>
                <div class="input-wrap">
                    <span class="input-icon-left"><i class="bi bi-lock-fill"></i></span>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Repeat your password" class="{{ $errors->get('password_confirmation') ? 'is-error' : '' }}">
                    <button type="button" class="input-eye" onclick="toggleVis('password_confirmation', this)"><i class="bi bi-eye-fill"></i></button>
                </div>
                @if ($errors->get('password_confirmation'))
                    <span class="err-text">{{ $errors->first('password_confirmation') }}</span>
                @endif
            </div>

            <button type="submit" class="btn-primary-st" id="rpBtn">
                <span class="btn-spinner"></span>
                <span class="btn-label" style="display:flex;align-items:center;gap:.55rem;">
                    <i class="bi bi-check-circle-fill"></i>
                    Reset Password
                </span>
            </button>
        </form>

    </div>

</div>

<script>
    function toggleVis(id, btn) {
        const inp = document.getElementById(id);
        const icon = btn.querySelector('i');
        if (inp.type === 'password') {
            inp.type = 'text';
            icon.className = 'bi bi-eye-slash-fill';
        } else {
            inp.type = 'password';
            icon.className = 'bi bi-eye-fill';
        }
    }

    // Simple password strength meter
    document.getElementById('password').addEventListener('input', function () {
        const val = this.value;
        const fill = document.getElementById('strengthFill');
        let score = 0;
        if (val.length >= 8)  score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        const widths = ['0%', '25%', '50%', '75%', '100%'];
        const colors = ['transparent', '#f87171', '#f59e0b', '#34d399', '#6366f1'];
        fill.style.width  = widths[score];
        fill.style.background = colors[score];
    });

    document.getElementById('rpForm').addEventListener('submit', function () {
        const btn = document.getElementById('rpBtn');
        btn.classList.add('loading');
        btn.disabled = true;
    });
</script>

</body>
</html>
