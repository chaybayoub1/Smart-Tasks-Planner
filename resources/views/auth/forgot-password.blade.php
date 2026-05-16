<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Forgot Password — {{ config('app.name', 'SmarTasker') }}</title>

    <!-- Fonts (identical to guest.blade.php) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons (identical to guest.blade.php) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- App styles + JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ─────────────────────────────────────────────────────
           RESET — identical to guest.blade.php
        ───────────────────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary:   #6366f1;
            --primary-d: #4f46e5;
            --accent:    #f59e0b;
        }

        /* ─────────────────────────────────────────────────────
           BODY — true full-viewport centering, no scroll
        ───────────────────────────────────────────────────── */
        html, body {
            height: 100%;
            font-family: 'Inter', sans-serif;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            /* prevent content from being clipped on very short viewports */
            overflow-y: auto;
            background: #1e1b4b;
        }

        /* ─────────────────────────────────────────────────────
           ANIMATED BACKGROUND — copied verbatim from guest.blade.php
        ───────────────────────────────────────────────────── */
        .bg-scene {
            position: fixed; inset: 0; z-index: 0; pointer-events: none;
            background: radial-gradient(ellipse 80% 60% at 20% 50%, #2d1f6e 0%, #1e1b4b 55%, #0f0c2e 100%);
            overflow: hidden;
        }
        .bg-scene::before {
            content: ''; position: absolute; inset: 0;
            background-image:
                radial-gradient(circle at 15% 25%, rgba(99,102,241,.18) 0%, transparent 45%),
                radial-gradient(circle at 85% 75%, rgba(245,158,11,.10) 0%, transparent 40%),
                radial-gradient(circle at 60% 10%, rgba(139,92,246,.12) 0%, transparent 35%);
        }
        .bg-scene::after {
            content: ''; position: absolute; inset: 0;
            background-image: radial-gradient(rgba(255,255,255,.07) 1px, transparent 1px);
            background-size: 32px 32px;
        }
        .orb {
            position: absolute; border-radius: 50%;
            filter: blur(70px); opacity: .35;
            animation: drift 12s ease-in-out infinite alternate;
        }
        .orb-1 { width:380px; height:380px; background:#6366f1; top:-120px; left:-80px; animation-duration:14s; }
        .orb-2 { width:260px; height:260px; background:#8b5cf6; bottom:10%; right:5%; animation-duration:10s; animation-delay:-4s; }
        .orb-3 { width:180px; height:180px; background:#f59e0b; bottom:25%; left:10%; animation-duration:16s; animation-delay:-7s; opacity:.2; }
        @keyframes drift {
            from { transform: translate(0,0) scale(1); }
            to   { transform: translate(30px,40px) scale(1.08); }
        }

        /* ─────────────────────────────────────────────────────
           CENTER WRAPPER — logo + card + back link stacked
        ───────────────────────────────────────────────────── */
        .fp-center {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 460px;
            padding: 1.5rem 1.25rem;
            margin: auto;               /* pushes away from both edges */
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.5rem;
            animation: slideUp .5s cubic-bezier(.16,1,.3,1) both;
        }
        @keyframes slideUp {
            from { opacity:0; transform: translateY(24px); }
            to   { opacity:1; transform: translateY(0); }
        }

        /* ─────────────────────────────────────────────────────
           LOGO — above the card
        ───────────────────────────────────────────────────── */
        .fp-logo {
            font-family: 'Sora', sans-serif;
            font-size: 1.55rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: -.03em;
            display: flex;
            align-items: center;
            gap: .3rem;
            text-decoration: none;
            user-select: none;
        }
        .fp-logo .dot { color: var(--accent); }

        /* ─────────────────────────────────────────────────────
           GLASS CARD — same recipe as .card-glass in guest.blade.php
        ───────────────────────────────────────────────────── */
        .fp-card {
            width: 100%;
            background: rgba(255,255,255,.07);
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 24px;
            padding: 2.5rem 2.25rem 2.25rem;
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            box-shadow: 0 24px 60px rgba(0,0,0,.35),
                        0 1px 0 rgba(255,255,255,.08) inset;
        }

        /* ─────────────────────────────────────────────────────
           CARD HEADER
        ───────────────────────────────────────────────────── */
        .fp-card-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        /* Animated shield icon */
        .fp-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            margin: 0 auto 1.2rem;
            background: linear-gradient(135deg, rgba(99,102,241,.32) 0%, rgba(79,70,229,.20) 100%);
            border: 1px solid rgba(99,102,241,.38);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.55rem;
            color: #a5b4fc;
            box-shadow: 0 8px 28px rgba(99,102,241,.22);
            animation: iconPulse 3s ease-in-out infinite;
        }
        @keyframes iconPulse {
            0%, 100% { box-shadow: 0 8px 28px rgba(99,102,241,.22); }
            50%       { box-shadow: 0 8px 38px rgba(99,102,241,.46); }
        }

        .fp-card-header h2 {
            font-family: 'Sora', sans-serif;
            font-size: 1.65rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: -.02em;
            margin-bottom: .45rem;
        }

        .fp-card-header p {
            color: rgba(255,255,255,.45);
            font-size: .875rem;
            line-height: 1.6;
            max-width: 300px;
            margin: 0 auto;
        }

        /* ─────────────────────────────────────────────────────
           SUCCESS BANNER — same token colours as guest.blade.php .status-msg
        ───────────────────────────────────────────────────── */
        .status-msg {
            background: rgba(99,102,241,.15);
            border: 1px solid rgba(99,102,241,.32);
            border-radius: 10px;
            padding: .7rem 1rem;
            color: #a5b4fc;
            font-size: .82rem;
            line-height: 1.5;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: .5rem;
        }
        .status-msg i { flex-shrink: 0; margin-top: .05rem; }

        /* ─────────────────────────────────────────────────────
           INPUT GROUP — identical to guest.blade.php
        ───────────────────────────────────────────────────── */
        .input-group-st {
            position: relative;
            margin-bottom: 1.25rem;
        }
        .input-group-st label {
            display: block;
            font-size: .72rem;
            font-weight: 600;
            color: rgba(255,255,255,.5);
            margin-bottom: .35rem;
            letter-spacing: .06em;
            text-transform: uppercase;
        }
        .input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }
        .input-icon-left {
            position: absolute; left: .9rem; top: 50%; transform: translateY(-50%);
            color: rgba(255,255,255,.35); font-size: 1rem;
            pointer-events: none; transition: color .2s; z-index: 2;
        }
        .input-wrap:focus-within .input-icon-left { color: #818cf8; }

        .input-group-st input[type="email"] {
            width: 100%;
            padding: .95rem 1.2rem .95rem 2.8rem;
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 12px;
            color: #fff;
            font-size: .95rem;
            font-family: 'Inter', sans-serif;
            outline: none;
            transition: border-color .2s, background .2s, box-shadow .2s;
        }
        .input-group-st input::placeholder { color: rgba(255,255,255,.25); }
        .input-wrap:focus-within input {
            border-color: rgba(99,102,241,.7);
            background: rgba(99,102,241,.1);
            box-shadow: 0 0 0 3px rgba(99,102,241,.15);
        }
        .input-group-st input.is-error { border-color: #f87171 !important; }

        .err-text {
            display: block;
            margin-top: .3rem;
            font-size: .78rem;
            color: #fca5a5;
        }

        /* ─────────────────────────────────────────────────────
           PRIMARY BUTTON — identical to guest.blade.php
        ───────────────────────────────────────────────────── */
        .btn-primary-st {
            width: 100%;
            padding: 1rem 1.75rem;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-family: 'Sora', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: .03em;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .55rem;
            transition: transform .15s, box-shadow .15s, filter .15s;
            box-shadow: 0 6px 24px rgba(99,102,241,.45);
        }
        .btn-primary-st:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(99,102,241,.6);
            filter: brightness(1.09);
        }
        .btn-primary-st:active { transform: translateY(0); }

        /* ─────────────────────────────────────────────────────
           BACK LINK — below the card
        ───────────────────────────────────────────────────── */
        .fp-back {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            font-size: .82rem;
            color: rgba(255,255,255,.35);
            text-decoration: none;
            transition: color .18s, gap .18s;
        }
        .fp-back i { font-size: .8rem; transition: transform .18s; }
        .fp-back:hover { color: #a5b4fc; gap: .6rem; }
        .fp-back:hover i { transform: translateX(-3px); }
    </style>
</head>
<body>

{{-- ── Animated background (same orbs + dot-grid as guest.blade.php) ── --}}
<div class="bg-scene">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
</div>

{{-- ── Centered column: logo → card → back link ── --}}
<div class="fp-center">

    {{-- Logo --}}
    <a href="{{ url('/') }}" class="fp-logo">
        🎓 Smar<span class="dot">Tasker</span>
    </a>

    {{-- Glass card --}}
    <div class="fp-card">

        {{-- Header --}}
        <div class="fp-card-header">
            <div class="fp-icon">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <h2>Forgot password?</h2>
            <p>Enter your email and we'll send you a secure link to reset it.</p>
        </div>

        {{-- Success status --}}
        @if (session('status'))
            <div class="status-msg" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('status') }}
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="input-group-st">
                <label for="email">Email address</label>
                <div class="input-wrap">
                    <span class="input-icon-left">
                        <i class="bi bi-envelope-fill"></i>
                    </span>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="you@university.edu"
                        required
                        autofocus
                        autocomplete="email"
                        class="{{ $errors->get('email') ? 'is-error' : '' }}"
                    >
                </div>
                @if ($errors->get('email'))
                    <span class="err-text">{{ $errors->first('email') }}</span>
                @endif
            </div>

            <button type="submit" class="btn-primary-st">
                <i class="bi bi-send-fill"></i>
                Send Reset Link
            </button>
        </form>

    </div>{{-- /.fp-card --}}

    {{-- Back to login --}}
    <a href="{{ route('login') }}" class="fp-back">
        <i class="bi bi-arrow-left"></i>
        Back to sign in
    </a>

</div>{{-- /.fp-center --}}

</body>
</html>