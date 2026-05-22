{{-- resources/views/layouts/guest.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SmarTasker') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --c-bg:     #0b0f1a;
            --c-surf:   #111827;
            --c-surf2:  #1a2235;
            --c-border: rgba(255,255,255,.07);
            --c-teal:   #00d4aa;
            --c-amber:  #f59e0b;
            --c-violet: #7c6af7;
            --c-text:   #e8edf5;
            --c-muted:  #6b7a99;
            --c-muted2: #94a3b8;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--c-bg);
            color: var(--c-text);
            min-height: 100vh;
            display: flex;
        }

        /* ── Animated background ──────────────────────────────── */
        .bg-scene {
            position: fixed; inset: 0; z-index: 0; pointer-events: none;
            background: radial-gradient(ellipse 80% 60% at 20% 50%, #0e1a32 0%, var(--c-bg) 55%, #060911 100%);
            overflow: hidden;
        }
        .bg-scene::before {
            content: ''; position: absolute; inset: 0;
            background-image:
                radial-gradient(circle at 15% 25%, rgba(0,212,170,.1) 0%, transparent 45%),
                radial-gradient(circle at 85% 75%, rgba(245,158,11,.08) 0%, transparent 40%),
                radial-gradient(circle at 60% 10%, rgba(124,106,247,.1) 0%, transparent 35%);
        }
        .bg-scene::after {
            content: ''; position: absolute; inset: 0;
            background-image: radial-gradient(rgba(255,255,255,.04) 1px, transparent 1px);
            background-size: 30px 30px;
        }
        .orb {
            position: absolute; border-radius: 50%;
            filter: blur(80px); opacity: .25;
            animation: drift 14s ease-in-out infinite alternate;
        }
        .orb-1 { width: 400px; height: 400px; background: var(--c-teal);  top: -150px; left: -100px; animation-duration: 16s; }
        .orb-2 { width: 280px; height: 280px; background: var(--c-violet); bottom: 5%; right: 3%;  animation-duration: 11s; animation-delay: -5s; }
        .orb-3 { width: 200px; height: 200px; background: var(--c-amber);  bottom: 30%; left: 8%;  animation-duration: 18s; animation-delay: -9s; opacity: .15; }
        @keyframes drift {
            from { transform: translate(0,0) scale(1); }
            to   { transform: translate(28px,36px) scale(1.07); }
        }

        /* ── Page layout ──────────────────────────────────────── */
        .auth-wrapper {
            position: relative; z-index: 10;
            width: 100%;
            display: flex;
            align-items: stretch;
        }

        /* ── Left branding panel (desktop) ───────────────────── */
        .panel-left {
            flex: 1;
            display: none;
            flex-direction: column;
            justify-content: center;
            padding: 4rem 5rem;
        }
        @media (min-width: 1024px) { .panel-left { display: flex; } }

        .brand-logo {
            font-family: 'Syne', sans-serif;
            font-size: 1.6rem; font-weight: 800;
            color: var(--c-text); letter-spacing: -.03em;
            margin-bottom: 2.75rem;
            display: flex; align-items: center; gap: .65rem;
        }
        .brand-logo .logo-icon {
            width: 40px; height: 40px; border-radius: 11px;
            background: linear-gradient(135deg, var(--c-teal), var(--c-violet));
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; color: #fff;
            box-shadow: 0 4px 16px rgba(0,212,170,.3);
        }
        .brand-logo .logo-text span { color: var(--c-teal); }

        .panel-left h1 {
            font-family: 'Syne', sans-serif;
            font-size: clamp(2rem, 3.2vw, 2.8rem);
            font-weight: 700; color: var(--c-text);
            line-height: 1.2; letter-spacing: -.03em;
            margin-bottom: 1rem;
        }
        .panel-left h1 em { font-style: normal; color: var(--c-teal); }

        .tagline {
            font-size: .95rem; color: var(--c-muted2);
            line-height: 1.75; margin-bottom: 2.25rem;
            max-width: 420px;
        }

        .feature-list { display: flex; flex-direction: column; gap: .8rem; margin-bottom: 2.5rem; }
        .feature-item {
            display: flex; align-items: center; gap: .85rem;
        }
        .fi-icon {
            width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0;
            background: rgba(0,212,170,.1);
            border: 1px solid rgba(0,212,170,.2);
            display: flex; align-items: center; justify-content: center;
            color: var(--c-teal); font-size: .95rem;
        }
        .feature-item span { font-size: .88rem; color: var(--c-muted2); line-height: 1.5; }

        .quote-block {
            padding: 1rem 1.25rem;
            border-left: 3px solid rgba(0,212,170,.4);
            background: rgba(0,212,170,.05);
            border-radius: 0 8px 8px 0;
            max-width: 440px;
        }
        .quote-block p {
            font-size: .88rem; color: var(--c-muted2);
            line-height: 1.7; font-style: italic; margin-bottom: .4rem;
        }
        .quote-block cite { font-size: .75rem; color: var(--c-muted); }

        /* ── Right form panel ─────────────────────────────────── */
        .panel-right {
            width: 100%;
            display: flex; align-items: center; justify-content: center;
            padding: 2rem 1.25rem;
        }
        @media (min-width: 1024px) { .panel-right { width: 440px; padding: 2rem; } }

        .form-card {
            width: 100%; max-width: 400px;
        }

        .card-glass {
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 20px;
            padding: 2.25rem 2rem;
            backdrop-filter: blur(20px);
        }

        /* ── Auth heading ─────────────────────────────────────── */
        .auth-title {
            font-family: 'Syne', sans-serif;
            font-size: 1.5rem; font-weight: 800;
            color: var(--c-text); letter-spacing: -.02em;
            margin-bottom: .35rem;
        }
        .auth-sub {
            font-size: .82rem; color: var(--c-muted);
            margin-bottom: 1.75rem;
        }

        /* ── Inputs ───────────────────────────────────────────── */
        .input-wrap {
            position: relative; margin-bottom: 1rem;
        }
        .input-wrap .input-icon {
            position: absolute; left: .9rem; top: 50%; transform: translateY(-50%);
            color: var(--c-muted); font-size: .95rem; pointer-events: none;
        }
        .input-wrap input {
            width: 100%;
            padding: .9rem .9rem .9rem 2.7rem;
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 11px;
            color: var(--c-text); font-size: .9rem; font-family: 'DM Sans', sans-serif;
            outline: none;
            transition: border-color .2s, background .2s, box-shadow .2s;
        }
        .input-wrap input::placeholder { color: rgba(255,255,255,.22); }
        .input-wrap:focus-within input {
            border-color: rgba(0,212,170,.55);
            background: rgba(0,212,170,.07);
            box-shadow: 0 0 0 3px rgba(0,212,170,.1);
        }
        .input-wrap .toggle-pw {
            position: absolute; right: .75rem; top: 50%; transform: translateY(-50%);
            background: none; border: none; color: var(--c-muted); cursor: pointer;
            font-size: 1rem; transition: color .15s; padding: 0;
        }
        .input-wrap .toggle-pw:hover { color: var(--c-teal); }
        .field-error {
            font-size: .74rem; color: #fca5a5; margin-top: .25rem; display: block;
        }

        /* ── Labels ───────────────────────────────────────────── */
        .field-label {
            display: block; font-size: .73rem; font-weight: 600;
            color: var(--c-muted2); margin-bottom: .35rem;
            text-transform: uppercase; letter-spacing: .07em;
        }

        /* ── Checkbox row ─────────────────────────────────────── */
        .row-between {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 1.4rem;
        }
        .checkbox-wrap { display: flex; align-items: center; gap: .5rem; }
        .checkbox-wrap input[type="checkbox"] {
            width: 15px; height: 15px; accent-color: var(--c-teal); cursor: pointer;
        }
        .checkbox-wrap label { font-size: .8rem; color: var(--c-muted); cursor: pointer; }
        .link-muted { font-size: .78rem; color: var(--c-muted); text-decoration: none; transition: color .15s; }
        .link-muted:hover { color: var(--c-teal); }

        /* ── Primary button ───────────────────────────────────── */
        .btn-auth-primary {
            width: 100%; padding: .95rem;
            background: linear-gradient(135deg, var(--c-teal) 0%, var(--c-violet) 100%);
            border: none; border-radius: 11px;
            color: #fff; font-family: 'Syne', sans-serif;
            font-size: 1rem; font-weight: 700; letter-spacing: .02em;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: .6rem;
            transition: transform .15s, box-shadow .15s, filter .15s;
            box-shadow: 0 6px 24px rgba(0,212,170,.25);
            margin-bottom: 1rem;
        }
        .btn-auth-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0,212,170,.35);
            filter: brightness(1.07);
        }
        .btn-auth-primary:active { transform: translateY(0); }

        /* ── Secondary / register button ──────────────────────── */
        .btn-auth-ghost {
            width: 100%; padding: .85rem;
            background: transparent;
            border: 1.5px solid rgba(245,158,11,.45);
            border-radius: 11px;
            color: var(--c-amber); font-family: 'Syne', sans-serif;
            font-size: .95rem; font-weight: 600;
            cursor: pointer; text-decoration: none;
            display: flex; align-items: center; justify-content: center; gap: .5rem;
            transition: background .18s, border-color .18s, box-shadow .18s;
        }
        .btn-auth-ghost:hover {
            background: rgba(245,158,11,.1);
            border-color: var(--c-amber);
            box-shadow: 0 4px 18px rgba(245,158,11,.18);
            color: var(--c-amber);
        }

        /* ── Divider ──────────────────────────────────────────── */
        .divider-or {
            display: flex; align-items: center; gap: .75rem;
            margin: 1rem 0; color: var(--c-muted); font-size: .72rem;
        }
        .divider-or::before, .divider-or::after {
            content: ''; flex: 1; height: 1px; background: rgba(255,255,255,.08);
        }

        /* ── Status message ───────────────────────────────────── */
        .status-msg {
            background: rgba(0,212,170,.1);
            border: 1px solid rgba(0,212,170,.25);
            border-radius: 8px; padding: .6rem .9rem;
            color: var(--c-teal); font-size: .8rem; margin-bottom: 1rem;
        }

        /* ── Laravel Breeze error text overrides ──────────────── */
        .text-red-600 { color: #fca5a5 !important; font-size: .74rem; }
        .mt-2 { margin-top: .2rem; display: block; }
    </style>
</head>
<body>

<div class="bg-scene">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
</div>

<div class="auth-wrapper">

    {{-- Left branding panel --}}
    <div class="panel-left">
        <div class="brand-logo">
            <div class="logo-icon"><i class="bi bi-lightning-charge-fill"></i></div>
            <div class="logo-text">Smar<span>Tasker</span></div>
        </div>

        <h1>Your academic<br><em>command centre.</em></h1>
        <p class="tagline">
            Plan sessions, track progress, and stay in the zone —
            all in one sleek workspace built for serious students.
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
                <span>Flashcards with spaced-repetition review</span>
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
    (function() {
        var quotes = [
            { text: "Success is the sum of small efforts repeated day in and day out.", author: "Robert Collier" },
            { text: "Focus on progress, not perfection.", author: "Bill Phillips" },
            { text: "The secret of getting ahead is getting started.", author: "Mark Twain" },
            { text: "An investment in knowledge pays the best interest.", author: "Benjamin Franklin" },
            { text: "Hard work beats talent when talent doesn't work hard.", author: "Tim Notke" },
            { text: "Don't watch the clock; do what it does — keep going.", author: "Sam Levenson" },
            { text: "Small daily improvements over time lead to stunning results.", author: "Robin Sharma" },
            { text: "The future belongs to those who prepare for it today.", author: "Malcolm X" },
        ];
        var q = quotes[Math.floor(Math.random() * quotes.length)];
        document.getElementById('quote-text').textContent   = '\u201C' + q.text + '\u201D';
        document.getElementById('quote-author').textContent = '\u2014 ' + q.author;
    })();
    </script>

    {{-- Right form panel --}}
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
