<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SmarTasker') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ── Reset ─────────────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary:   #6366f1;
            --primary-d: #4f46e5;
            --accent:    #f59e0b;
        }

        html { height: 100%; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            /* NEVER overflow:hidden — that clips the form on small screens */
            overflow-y: auto;
            background: #1e1b4b;
            display: flex;
        }

        /* ── Animated background (fixed, never clips content) ──── */
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

        /* ── Page wrapper ───────────────────────────────────────── */
        .auth-wrapper {
            position: relative; z-index: 10;
            width: 100%;
            display: flex;
            align-items: stretch;
        }

        /* ── Left branding panel ────────────────────────────────── */
        .panel-left {
            flex: 1;
            display: none;
            flex-direction: column;
            justify-content: center;
            padding: 4rem 5rem;
        }
        @media (min-width: 1024px) { .panel-left { display: flex; } }

        .panel-left .brand-logo {
            font-family: 'Sora', sans-serif;
            font-size: 2rem; font-weight: 800;
            color: #fff; letter-spacing: -.03em; margin-bottom: 2.5rem;
        }
        .panel-left .brand-logo span { color: var(--accent); }

        .panel-left h1 {
            font-family: 'Sora', sans-serif;
            font-size: clamp(2rem, 3.5vw, 3rem);
            font-weight: 700; color: #fff;
            line-height: 1.2; letter-spacing: -.03em; margin-bottom: 1.25rem;
        }
        .panel-left h1 em {
            font-style: normal;
            background: linear-gradient(135deg, #a5b4fc, #818cf8);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .panel-left p.tagline {
            color: rgba(255,255,255,.55);
            font-size: 1.05rem; line-height: 1.7;
            max-width: 420px; margin-bottom: 3rem;
        }
        .feature-list { display: flex; flex-direction: column; gap: 1rem; }
        .feature-item {
            display: flex; align-items: center; gap: 1rem;
            color: rgba(255,255,255,.7); font-size: .9rem;
        }
        .feature-item .fi-icon {
            width: 36px; height: 36px; border-radius: 10px;
            background: rgba(99,102,241,.25); border: 1px solid rgba(99,102,241,.35);
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; color: #a5b4fc; flex-shrink: 0;
        }
        .quote-block {
            margin-top: 3.5rem; padding: 1.25rem 1.5rem;
            border-left: 3px solid rgba(245,158,11,.5);
            background: rgba(255,255,255,.04); border-radius: 0 10px 10px 0;
        }
        .quote-block p { color: rgba(255,255,255,.6); font-size: .875rem; font-style: italic; line-height: 1.6; }
        .quote-block cite { display: block; margin-top: .5rem; color: var(--accent); font-size: .78rem; font-style: normal; font-weight: 500; }

        /* ── Right form panel ───────────────────────────────────── */
        .panel-right {
            width: 100%;
            display: flex;
            /* align-items: flex-start so the form isn't clipped when shorter than viewport */
            align-items: flex-start;
            justify-content: center;
            padding: 2rem 1.25rem;
        }
        @media (min-width: 640px) {
            .panel-right { align-items: center; padding: 3rem 2rem; }
        }
        @media (min-width: 1024px) {
            .panel-right {
                width: 580px; flex-shrink: 0;
                background: rgba(255,255,255,.03);
                border-left: 1px solid rgba(255,255,255,.07);
                backdrop-filter: blur(20px);
                /* scroll within column when viewport is very short */
                overflow-y: auto;
                max-height: 100vh;
                padding: 2.5rem 2rem;
                align-items: center;
            }
        }

        /* ── Form card ──────────────────────────────────────────── */
        .form-card {
            width: 100%; max-width: 520px;
            animation: slideUp .5s cubic-bezier(.16,1,.3,1) both;
        }
        @keyframes slideUp {
            from { opacity:0; transform: translateY(24px); }
            to   { opacity:1; transform: translateY(0); }
        }

        .card-glass {
            background: rgba(255,255,255,.07);
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 24px;
            padding: 2.75rem 2.5rem;
            backdrop-filter: blur(24px);
            box-shadow: 0 24px 60px rgba(0,0,0,.35), 0 1px 0 rgba(255,255,255,.08) inset;
        }

        /* Mobile brand mark */
        .brand-mobile {
            font-family: 'Sora', sans-serif;
            font-weight: 800; font-size: 1.4rem;
            color: #fff; letter-spacing: -.03em;
            margin-bottom: 1.5rem;
            display: flex; align-items: center; gap: .4rem;
        }
        .brand-mobile .dot { color: var(--accent); }
        @media (min-width: 1024px) { .brand-mobile { display: none; } }

        /* ── Heading / subtitle ─────────────────────────────────── */
        .card-glass h2 {
            font-family: 'Sora', sans-serif;
            font-size: 1.75rem; font-weight: 700;
            color: #fff; letter-spacing: -.02em; margin-bottom: .4rem;
        }
        .card-glass .subtitle {
            color: rgba(255,255,255,.45); font-size: .9rem; margin-bottom: 2rem;
        }

        /* ── Input groups ───────────────────────────────────────── */
        .input-group-st { position: relative; margin-bottom: 1.25rem; }

        .input-group-st label {
            display: block; font-size: .72rem; font-weight: 600;
            color: rgba(255,255,255,.5); margin-bottom: .35rem;
            letter-spacing: .06em; text-transform: uppercase;
        }

        /* inner wrapper: positions icons relative to the input row only */
        .input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        /* left decorative icon — centred inside input row */
        .input-icon-left {
            position: absolute; left: .9rem; top: 50%; transform: translateY(-50%);
            color: rgba(255,255,255,.35); font-size: 1rem;
            pointer-events: none; transition: color .2s; z-index: 2;
        }
        .input-wrap:focus-within .input-icon-left { color: #818cf8; }

        /* right toggle button — centred inside input row */
        .input-icon-right {
            position: absolute; right: 0; top: 0; bottom: 0;
            width: 50px;
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,.45); font-size: 1.1rem;
            background: none; border: none;
            cursor: pointer; z-index: 2;
            border-radius: 0 12px 12px 0;
            transition: color .2s, background .2s;
        }
        .input-icon-right:hover {
            color: #c7d2fe;
            background: rgba(165,180,252,.08);
        }
        .input-icon-right:active { color: #a5b4fc; }
        .input-icon-right.active { color: #a5b4fc; }

        .input-group-st input[type="email"],
        .input-group-st input[type="password"],
        .input-group-st input[type="text"] {
            width: 100%;
            padding: .95rem 3rem .95rem 2.8rem;
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 12px;
            color: #fff; font-size: .95rem; font-family: 'Inter', sans-serif;
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

        /* Breeze error text */
        .text-red-600 { color: #fca5a5 !important; }
        .mt-2 { margin-top: .3rem; display: block; font-size: .78rem; }

        /* ── Remember / forgot row ──────────────────────────────── */
        .row-between {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 1.4rem; margin-top: .15rem;
        }
        .checkbox-wrap { display: flex; align-items: center; gap: .55rem; }
        .checkbox-wrap input[type="checkbox"] {
            width: 16px; height: 16px;
            accent-color: var(--primary); cursor: pointer;
        }
        .checkbox-wrap label { font-size: .83rem; color: rgba(255,255,255,.5); cursor: pointer; }

        .link-muted {
            font-size: .8rem; color: rgba(255,255,255,.38);
            text-decoration: none; transition: color .15s;
        }
        .link-muted:hover { color: #a5b4fc; }

        /* ── Primary login button ───────────────────────────────── */
        .btn-primary-st {
            width: 100%; padding: 1.05rem 1.75rem;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            border: none; border-radius: 12px;
            color: #fff; font-family: 'Sora', sans-serif;
            font-size: 1.05rem; font-weight: 700; letter-spacing: .03em;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: .6rem;
            transition: transform .15s, box-shadow .15s, filter .15s;
            box-shadow: 0 6px 24px rgba(99,102,241,.45);
        }
        .btn-primary-st:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(99,102,241,.6);
            filter: brightness(1.09);
        }
        .btn-primary-st:active { transform: translateY(0); }

        /* ── Divider ────────────────────────────────────────────── */
        .divider-or {
            display: flex; align-items: center; gap: .75rem;
            margin: 1.1rem 0;
        }
        .divider-or::before, .divider-or::after {
            content: ''; flex: 1; height: 1px; background: rgba(255,255,255,.1);
        }
        .divider-or span { font-size: .72rem; color: rgba(255,255,255,.3); }

        /* ── Register button (amber, clearly visible) ───────────── */
        .btn-register-st {
            width: 100%; padding: .9rem 1.75rem;
            background: linear-gradient(135deg, rgba(245,158,11,.20) 0%, rgba(245,158,11,.12) 100%);
            border: 1.5px solid rgba(245,158,11,.6); border-radius: 12px;
            color: #fcd34d; font-family: 'Sora', sans-serif;
            font-size: .95rem; font-weight: 600; letter-spacing: .01em;
            cursor: pointer; text-decoration: none;
            display: flex; align-items: center; justify-content: center; gap: .5rem;
            transition: background .18s, border-color .18s, color .18s, box-shadow .18s;
        }
        .btn-register-st:hover {
            background: linear-gradient(135deg, rgba(245,158,11,.32) 0%, rgba(245,158,11,.22) 100%);
            border-color: rgba(245,158,11,.9);
            color: #fef3c7;
            box-shadow: 0 4px 20px rgba(245,158,11,.22);
        }

        /* ── Footer links ───────────────────────────────────────── */
        .links-row {
            display: flex; justify-content: space-between; align-items: center;
            margin-top: 1.4rem;
        }
        .links-row span { font-size: .72rem; color: rgba(255,255,255,.22); }

        /* ── Status message ─────────────────────────────────────── */
        .status-msg {
            background: rgba(99,102,241,.15);
            border: 1px solid rgba(99,102,241,.3);
            border-radius: 8px; padding: .6rem .9rem;
            color: #a5b4fc; font-size: .8rem; margin-bottom: 1.1rem;
        }
    </style>
</head>
<body>

<div class="bg-scene">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
</div>

<div class="auth-wrapper">

    {{-- ── Left branding panel (desktop only) ── --}}
    <div class="panel-left">
        <div class="brand-logo">🎓 Smar<span>Tasker</span></div>

        <h1>Your academic<br><em>command centre.</em></h1>
        <p class="tagline">
            Plan sessions, track progress, and stay in the zone —
            all in one beautiful workspace built for students.
        </p>

        <div class="feature-list">
            <div class="feature-item">
                <div class="fi-icon"><i class="bi bi-check2-square"></i></div>
                <span>Intelligent study planner with smart reminders</span>
            </div>
            <div class="feature-item">
                <div class="fi-icon"><i class="bi bi-stopwatch-fill"></i></div>
                <span>Pomodoro timer with focus streak tracking</span>
            </div>
            <div class="feature-item">
                <div class="fi-icon"><i class="bi bi-layers-fill"></i></div>
                <span>Flashcards &amp; spaced-repetition review</span>
            </div>
            <div class="feature-item">
                <div class="fi-icon"><i class="bi bi-bar-chart-fill"></i></div>
                <span>XP, levels &amp; streak rewards to keep you going</span>
            </div>
        </div>

        <div class="quote-block">
            <p id="quote-text"></p>
            <cite id="quote-author"></cite>
        </div>
    </div>

    <script>
        (function () {
            var quotes = [
                { text: "Success is the sum of small efforts repeated day in and day out.", author: "Robert Collier" },
                { text: "Focus on progress, not perfection.", author: "Bill Phillips" },
                { text: "Discipline beats motivation — motivation is fleeting, discipline is forever.", author: "Anonymous" },
                { text: "The secret of getting ahead is getting started.", author: "Mark Twain" },
                { text: "Productivity is never an accident. It is always the result of a commitment to excellence.", author: "Paul J. Meyer" },
                { text: "An investment in knowledge pays the best interest.", author: "Benjamin Franklin" },
                { text: "You don't have to be great to start, but you have to start to be great.", author: "Zig Ziglar" },
                { text: "It always seems impossible until it's done.", author: "Nelson Mandela" },
                { text: "The future belongs to those who prepare for it today.", author: "Malcolm X" },
                { text: "Small daily improvements over time lead to stunning results.", author: "Robin Sharma" },
                { text: "Don't watch the clock; do what it does — keep going.", author: "Sam Levenson" },
                { text: "Hard work beats talent when talent doesn't work hard.", author: "Tim Notke" }
            ];
            var q = quotes[Math.floor(Math.random() * quotes.length)];
            document.getElementById('quote-text').textContent   = '\u201C' + q.text + '\u201D';
            document.getElementById('quote-author').textContent = '\u2014 ' + q.author;
        })();
    </script>

    {{-- ── Right form panel ── --}}
    <div class="panel-right">
        <div class="form-card">
            <div class="card-glass">
                {{ $slot }}
            </div>
        </div>
    </div>

</div>

</body>
</html>