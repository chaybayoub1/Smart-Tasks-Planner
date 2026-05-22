{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SmarTasker') — SmarTasker</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    {{-- Google Fonts: Syne (display) + DM Sans (body) --}}
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        /* ══════════════════════════════════════════════
           DESIGN TOKENS
        ══════════════════════════════════════════════ */
        :root {
            --c-bg:        #0b0f1a;
            --c-surface:   #111827;
            --c-surface2:  #1a2235;
            --c-border:    rgba(255,255,255,.07);
            --c-border2:   rgba(255,255,255,.12);

            --c-teal:      #00d4aa;
            --c-teal-d:    #00b891;
            --c-teal-glow: rgba(0,212,170,.18);
            --c-amber:     #f59e0b;
            --c-amber-d:   #d97706;
            --c-amber-glow:rgba(245,158,11,.18);
            --c-coral:     #ff6b6b;
            --c-violet:    #7c6af7;

            --c-text:      #e8edf5;
            --c-muted:     #6b7a99;
            --c-muted2:    #94a3b8;

            --radius-sm:   8px;
            --radius-md:   14px;
            --radius-lg:   20px;

            --sidebar-w:   256px;
            --font-display:'Syne', sans-serif;
            --font-body:   'DM Sans', sans-serif;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: var(--font-body);
            background: var(--c-bg);
            color: var(--c-text);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ── Scrollbar ───────────────────────────────────── */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--c-border2); border-radius: 99px; }

        /* ══════════════════════════════════════════════
           SIDEBAR
        ══════════════════════════════════════════════ */
        #sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: var(--c-surface);
            position: fixed; top: 0; left: 0; z-index: 200;
            display: flex; flex-direction: column;
            border-right: 1px solid var(--c-border);
            transition: transform .28s cubic-bezier(.4,0,.2,1);
        }

        /* Subtle animated gradient top line */
        #sidebar::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 2px;
            background: linear-gradient(90deg, var(--c-teal), var(--c-violet), var(--c-amber));
            background-size: 200% 100%;
            animation: grad-shift 4s linear infinite;
        }
        @keyframes grad-shift {
            0%   { background-position: 0% 0%; }
            100% { background-position: 200% 0%; }
        }

        .sidebar-brand {
            padding: 1.4rem 1.5rem 1.2rem;
            display: flex; align-items: center; gap: .75rem;
            border-bottom: 1px solid var(--c-border);
            text-decoration: none;
        }
        .sidebar-brand .brand-icon {
            width: 36px; height: 36px; border-radius: 10px;
            background: linear-gradient(135deg, var(--c-teal), var(--c-violet));
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; color: #fff; flex-shrink: 0;
            box-shadow: 0 4px 14px rgba(0,212,170,.3);
        }
        .sidebar-brand .brand-text {
            font-family: var(--font-display);
            font-size: 1.15rem; font-weight: 800;
            color: var(--c-text); letter-spacing: -.01em;
        }
        .sidebar-brand .level-pill {
            font-size: .65rem; font-weight: 700;
            background: linear-gradient(135deg, var(--c-amber), var(--c-coral));
            color: #1a0a00; padding: 2px 9px; border-radius: 99px;
            letter-spacing: .04em;
        }

        /* Nav sections */
        .nav-label {
            padding: .75rem 1.5rem .3rem;
            font-size: .65rem; font-weight: 600;
            text-transform: uppercase; letter-spacing: .12em;
            color: var(--c-muted);
        }

        #sidebar nav a {
            display: flex; align-items: center; gap: .7rem;
            padding: .6rem 1.5rem; margin: 1px 0;
            color: var(--c-muted2); text-decoration: none;
            font-size: .875rem; font-weight: 500;
            border-radius: 0;
            transition: color .15s, background .15s;
            position: relative;
        }
        #sidebar nav a .nav-icon {
            width: 30px; height: 30px; border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center;
            font-size: .95rem; flex-shrink: 0;
            background: rgba(255,255,255,.05);
            transition: background .15s, box-shadow .15s;
        }
        #sidebar nav a:hover {
            color: var(--c-text);
            background: rgba(255,255,255,.04);
        }
        #sidebar nav a:hover .nav-icon {
            background: rgba(0,212,170,.15);
            color: var(--c-teal);
        }
        #sidebar nav a.active {
            color: var(--c-teal);
            background: var(--c-teal-glow);
        }
        #sidebar nav a.active::before {
            content: '';
            position: absolute; left: 0; top: 0; bottom: 0; width: 3px;
            background: var(--c-teal);
            border-radius: 0 3px 3px 0;
        }
        #sidebar nav a.active .nav-icon {
            background: rgba(0,212,170,.2);
            color: var(--c-teal);
            box-shadow: 0 0 10px rgba(0,212,170,.2);
        }

        /* XP Footer */
        .sidebar-footer {
            margin-top: auto;
            padding: 1rem 1.4rem 1.25rem;
            border-top: 1px solid var(--c-border);
        }
        .xp-meta {
            display: flex; justify-content: space-between;
            margin-bottom: .5rem;
        }
        .xp-meta span { font-size: .72rem; color: var(--c-muted); font-weight: 500; }
        .xp-meta .xp-val { color: var(--c-amber); }

        .xp-track {
            height: 5px; background: rgba(255,255,255,.08);
            border-radius: 99px; overflow: hidden;
        }
        .xp-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--c-teal), var(--c-violet));
            border-radius: 99px;
            transition: width .6s cubic-bezier(.4,0,.2,1);
            box-shadow: 0 0 8px rgba(0,212,170,.4);
        }

        .streak-badge {
            display: flex; align-items: center; gap: .4rem;
            margin-top: .7rem; padding: .45rem .75rem;
            background: rgba(245,158,11,.1);
            border: 1px solid rgba(245,158,11,.2);
            border-radius: var(--radius-sm);
            font-size: .78rem; color: var(--c-amber); font-weight: 600;
        }
        .streak-badge .flame {
            font-size: 1rem;
            animation: flicker 1.6s ease-in-out infinite;
        }
        @keyframes flicker {
            0%,100% { transform: scale(1) rotate(-3deg); }
            50%      { transform: scale(1.15) rotate(3deg); }
        }

        .sidebar-logout {
            display: flex; align-items: center; gap: .5rem;
            margin-top: .75rem; padding: .5rem .75rem;
            background: transparent; border: 1px solid var(--c-border);
            border-radius: var(--radius-sm);
            color: var(--c-muted); font-size: .8rem; font-weight: 500;
            cursor: pointer; width: 100%;
            transition: all .15s;
        }
        .sidebar-logout:hover {
            border-color: rgba(255,107,107,.3);
            color: var(--c-coral);
            background: rgba(255,107,107,.07);
        }

        /* ══════════════════════════════════════════════
           MAIN CONTENT
        ══════════════════════════════════════════════ */
        #main-content {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            background: var(--c-bg);
        }

        /* Topbar */
        .topbar {
            background: rgba(11,15,26,.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--c-border);
            padding: .7rem 1.75rem;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 100;
        }
        .topbar-title {
            font-family: var(--font-display);
            font-size: 1rem; font-weight: 700;
            color: var(--c-text); letter-spacing: -.01em;
        }
        .topbar-right { display: flex; align-items: center; gap: .75rem; }

        .xp-chip {
            display: inline-flex; align-items: center; gap: .35rem;
            padding: .3rem .7rem;
            background: rgba(245,158,11,.12);
            border: 1px solid rgba(245,158,11,.25);
            border-radius: 99px;
            font-size: .78rem; font-weight: 700; color: var(--c-amber);
        }
        .user-chip {
            font-size: .82rem; color: var(--c-muted2); font-weight: 500;
        }
        .topbar-btn {
            display: inline-flex; align-items: center; gap: .35rem;
            padding: .35rem .75rem;
            background: var(--c-surface);
            border: 1px solid var(--c-border);
            border-radius: var(--radius-sm);
            color: var(--c-muted); font-size: .78rem; font-weight: 600;
            cursor: pointer; transition: all .15s; text-decoration: none;
        }
        .topbar-btn:hover {
            border-color: rgba(255,107,107,.3);
            color: var(--c-coral);
            background: rgba(255,107,107,.07);
        }

        /* Mobile sidebar toggle */
        .sidebar-toggle {
            display: none;
            background: var(--c-surface);
            border: 1px solid var(--c-border);
            color: var(--c-muted2);
            padding: .35rem .6rem; border-radius: var(--radius-sm);
            cursor: pointer; font-size: 1.1rem;
        }

        /* ══════════════════════════════════════════════
           CONTENT WRAPPER
        ══════════════════════════════════════════════ */
        .content-wrap {
            padding: 1.75rem;
        }

        /* ══════════════════════════════════════════════
           ALERT / FLASH
        ══════════════════════════════════════════════ */
        .flash-wrap { padding: 0 1.75rem; margin-top: 1rem; }
        .st-alert {
            display: flex; align-items: center; gap: .6rem;
            padding: .7rem 1rem; border-radius: var(--radius-sm);
            font-size: .85rem; font-weight: 500; margin-bottom: .5rem;
            animation: slide-in .25s ease;
        }
        @keyframes slide-in {
            from { opacity:0; transform: translateY(-6px); }
            to   { opacity:1; transform: translateY(0); }
        }
        .st-alert-success {
            background: rgba(0,212,170,.1);
            border: 1px solid rgba(0,212,170,.25);
            color: var(--c-teal);
        }
        .st-alert-error {
            background: rgba(255,107,107,.1);
            border: 1px solid rgba(255,107,107,.25);
            color: var(--c-coral);
        }
        .st-alert .close-btn {
            margin-left: auto; background: none; border: none;
            color: inherit; opacity: .6; cursor: pointer; font-size: 1rem;
        }
        .st-alert .close-btn:hover { opacity: 1; }

        /* ══════════════════════════════════════════════
           CARD SYSTEM
        ══════════════════════════════════════════════ */
        .st-card {
            background: var(--c-surface);
            border: 1px solid var(--c-border);
            border-radius: var(--radius-lg);
            overflow: hidden;
        }
        .st-card-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--c-border);
        }
        .st-card-title {
            font-family: var(--font-display);
            font-size: .9rem; font-weight: 700;
            color: var(--c-text); letter-spacing: -.01em;
            display: flex; align-items: center; gap: .5rem;
        }
        .st-card-title .icon {
            width: 26px; height: 26px; border-radius: 7px;
            display: flex; align-items: center; justify-content: center;
            font-size: .8rem;
        }
        .icon-teal  { background: rgba(0,212,170,.15); color: var(--c-teal); }
        .icon-amber { background: rgba(245,158,11,.15); color: var(--c-amber); }
        .icon-coral { background: rgba(255,107,107,.15); color: var(--c-coral); }
        .icon-violet{ background: rgba(124,106,247,.15); color: var(--c-violet); }

        /* ══════════════════════════════════════════════
           STAT CARDS
        ══════════════════════════════════════════════ */
        .stat-card {
            background: var(--c-surface);
            border: 1px solid var(--c-border);
            border-radius: var(--radius-lg);
            padding: 1.25rem 1.4rem;
            position: relative; overflow: hidden;
            transition: transform .2s, box-shadow .2s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 32px rgba(0,0,0,.25);
        }
        .stat-card::after {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 2px;
        }
        .stat-card.teal::after  { background: linear-gradient(90deg,var(--c-teal),transparent); }
        .stat-card.amber::after { background: linear-gradient(90deg,var(--c-amber),transparent); }
        .stat-card.coral::after { background: linear-gradient(90deg,var(--c-coral),transparent); }
        .stat-card.violet::after{ background: linear-gradient(90deg,var(--c-violet),transparent); }

        .stat-bg-icon {
            position: absolute; right: 1rem; bottom: .75rem;
            font-size: 3rem; opacity: .08;
        }
        .stat-value {
            font-family: var(--font-display);
            font-size: 2rem; font-weight: 800; line-height: 1;
            letter-spacing: -.03em;
        }
        .stat-card.teal  .stat-value { color: var(--c-teal); }
        .stat-card.amber .stat-value { color: var(--c-amber); }
        .stat-card.coral .stat-value { color: var(--c-coral); }
        .stat-card.violet .stat-value { color: var(--c-violet); }

        .stat-label {
            font-size: .78rem; color: var(--c-muted); margin-top: .3rem;
            font-weight: 500; text-transform: uppercase; letter-spacing: .06em;
        }

        /* ══════════════════════════════════════════════
           BUTTONS
        ══════════════════════════════════════════════ */
        .btn-st-primary {
            display: inline-flex; align-items: center; gap: .45rem;
            padding: .55rem 1.1rem;
            background: linear-gradient(135deg, var(--c-teal), var(--c-violet));
            border: none; border-radius: var(--radius-sm);
            color: #fff; font-size: .85rem; font-weight: 600;
            cursor: pointer; transition: opacity .15s, transform .15s;
            text-decoration: none;
        }
        .btn-st-primary:hover  { opacity: .88; transform: translateY(-1px); color: #fff; }
        .btn-st-primary:active { transform: translateY(0); }

        .btn-st-ghost {
            display: inline-flex; align-items: center; gap: .4rem;
            padding: .45rem .85rem;
            background: transparent;
            border: 1px solid var(--c-border2);
            border-radius: var(--radius-sm);
            color: var(--c-muted2); font-size: .8rem; font-weight: 500;
            cursor: pointer; transition: all .15s; text-decoration: none;
        }
        .btn-st-ghost:hover { border-color: var(--c-teal); color: var(--c-teal); background: var(--c-teal-glow); }

        .btn-st-danger {
            display: inline-flex; align-items: center; gap: .4rem;
            padding: .45rem .85rem;
            background: rgba(255,107,107,.1);
            border: 1px solid rgba(255,107,107,.25);
            border-radius: var(--radius-sm);
            color: var(--c-coral); font-size: .8rem; font-weight: 500;
            cursor: pointer; transition: all .15s; text-decoration: none;
        }
        .btn-st-danger:hover { background: rgba(255,107,107,.18); color: var(--c-coral); }

        /* ══════════════════════════════════════════════
           FORM ELEMENTS
        ══════════════════════════════════════════════ */
        .st-label {
            display: block; font-size: .77rem; font-weight: 600;
            color: var(--c-muted2); margin-bottom: .35rem;
            text-transform: uppercase; letter-spacing: .06em;
        }
        .st-input, .st-select, .st-textarea {
            width: 100%;
            background: var(--c-surface2);
            border: 1px solid var(--c-border2);
            border-radius: var(--radius-sm);
            color: var(--c-text);
            padding: .55rem .85rem;
            font-size: .875rem; font-family: var(--font-body);
            transition: border-color .15s, box-shadow .15s;
            outline: none;
        }
        .st-input::placeholder { color: var(--c-muted); }
        .st-input:focus, .st-select:focus, .st-textarea:focus {
            border-color: var(--c-teal);
            box-shadow: 0 0 0 3px rgba(0,212,170,.12);
        }
        .st-select option { background: var(--c-surface2); }

        /* ══════════════════════════════════════════════
           BADGES / TAGS
        ══════════════════════════════════════════════ */
        .tag {
            display: inline-flex; align-items: center; gap: .3rem;
            padding: .2rem .6rem; border-radius: 99px;
            font-size: .72rem; font-weight: 600; letter-spacing: .02em;
        }
        .tag-teal   { background: rgba(0,212,170,.12); color: var(--c-teal); border: 1px solid rgba(0,212,170,.2); }
        .tag-amber  { background: rgba(245,158,11,.12); color: var(--c-amber); border: 1px solid rgba(245,158,11,.2); }
        .tag-coral  { background: rgba(255,107,107,.12); color: var(--c-coral); border: 1px solid rgba(255,107,107,.2); }
        .tag-violet { background: rgba(124,106,247,.12); color: var(--c-violet); border: 1px solid rgba(124,106,247,.2); }
        .tag-muted  { background: rgba(255,255,255,.06); color: var(--c-muted2); border: 1px solid var(--c-border); }

        /* ══════════════════════════════════════════════
           PROGRESS BAR
        ══════════════════════════════════════════════ */
        .st-progress {
            height: 6px; background: rgba(255,255,255,.07);
            border-radius: 99px; overflow: hidden;
        }
        .st-progress-fill {
            height: 100%; border-radius: 99px;
            transition: width .6s cubic-bezier(.4,0,.2,1);
        }
        .st-progress-fill.teal  { background: linear-gradient(90deg,var(--c-teal),var(--c-violet)); box-shadow: 0 0 8px rgba(0,212,170,.35); }
        .st-progress-fill.amber { background: linear-gradient(90deg,var(--c-amber),var(--c-coral)); }

        /* ══════════════════════════════════════════════
           LIST ITEMS
        ══════════════════════════════════════════════ */
        .st-list-item {
            display: flex; align-items: center; gap: .85rem;
            padding: .75rem 1.25rem;
            border-bottom: 1px solid var(--c-border);
            transition: background .12s;
        }
        .st-list-item:last-child { border-bottom: none; }
        .st-list-item:hover { background: rgba(255,255,255,.025); }

        /* ══════════════════════════════════════════════
           POMODORO TIMER
        ══════════════════════════════════════════════ */
        #timer-display {
            font-family: var(--font-display);
            font-size: 5rem; font-weight: 800;
            letter-spacing: -.04em; color: var(--c-teal);
            line-height: 1;
            text-shadow: 0 0 40px rgba(0,212,170,.3);
        }

        /* ══════════════════════════════════════════════
           FLASHCARD FLIP
        ══════════════════════════════════════════════ */
        .flip-card { perspective: 1000px; cursor: pointer; }
        .flip-card-inner {
            transition: transform .5s cubic-bezier(.4,0,.2,1);
            transform-style: preserve-3d; position: relative;
        }
        .flip-card.flipped .flip-card-inner { transform: rotateY(180deg); }
        .flip-card-front, .flip-card-back {
            backface-visibility: hidden;
            border-radius: var(--radius-lg);
            padding: 2.5rem; min-height: 220px;
            display: flex; align-items: center;
            justify-content: center; text-align: center;
        }
        .flip-card-front {
            background: var(--c-surface2);
            border: 1px solid var(--c-border2);
        }
        .flip-card-back {
            transform: rotateY(180deg);
            position: absolute; top: 0; left: 0; width: 100%;
            background: linear-gradient(135deg, rgba(0,212,170,.12), rgba(124,106,247,.12));
            border: 1px solid rgba(0,212,170,.25);
        }

        /* ══════════════════════════════════════════════
           CALENDAR
        ══════════════════════════════════════════════ */
        .cal-day {
            width: 30px; height: 30px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: .78rem; font-weight: 500; cursor: default;
            margin: 2px auto; transition: background .12s;
            color: var(--c-muted2);
        }
        .cal-day.today {
            background: var(--c-teal);
            color: var(--c-bg); font-weight: 700;
            box-shadow: 0 0 10px rgba(0,212,170,.3);
        }
        .cal-day.has-task {
            background: rgba(0,212,170,.12);
            color: var(--c-teal);
            border: 1px solid rgba(0,212,170,.2);
        }

        /* ══════════════════════════════════════════════
           EMPTY STATE
        ══════════════════════════════════════════════ */
        .empty-state {
            text-align: center; padding: 3rem 1rem; color: var(--c-muted);
        }
        .empty-state-icon {
            width: 56px; height: 56px; border-radius: var(--radius-md);
            background: rgba(255,255,255,.05);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; margin: 0 auto 1rem; color: var(--c-muted2);
        }
        .empty-state p { font-size: .85rem; line-height: 1.6; }
        .empty-state a { color: var(--c-teal); text-decoration: none; }
        .empty-state a:hover { text-decoration: underline; }

        /* ══════════════════════════════════════════════
           SECTION HEADING
        ══════════════════════════════════════════════ */
        .section-title {
            font-family: var(--font-display);
            font-size: 1.4rem; font-weight: 800;
            color: var(--c-text); letter-spacing: -.02em;
            margin-bottom: 1.25rem;
        }
        .section-title span { color: var(--c-teal); }

        /* ══════════════════════════════════════════════
           RESPONSIVE
        ══════════════════════════════════════════════ */
        @media (max-width: 768px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.show { transform: translateX(0); }
            #main-content { margin-left: 0; }
            .sidebar-toggle { display: block; }
            .content-wrap { padding: 1.25rem; }
        }
    </style>

    @stack('styles')
</head>
<body>

{{-- ── SIDEBAR ──────────────────────────────────────────────── --}}
<div id="sidebar">
    <a class="sidebar-brand" href="{{ route('dashboard') }}">
        <div class="brand-icon"><i class="bi bi-lightning-charge-fill"></i></div>
        <div>
            <div class="brand-text">SmarTasker</div>
        </div>
        <span class="level-pill ms-auto">Lv.{{ auth()->user()->level }}</span>
    </a>

    <nav class="mt-1">
        <div class="nav-label">Main</div>

        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <div class="nav-icon"><i class="bi bi-grid-1x2-fill"></i></div>
            Dashboard
        </a>
        <a href="{{ route('tasks.index') }}" class="{{ request()->routeIs('tasks.*') ? 'active' : '' }}">
            <div class="nav-icon"><i class="bi bi-check2-square"></i></div>
            Study Planner
        </a>
        <a href="{{ route('pomodoro.index') }}" class="{{ request()->routeIs('pomodoro.*') ? 'active' : '' }}">
            <div class="nav-icon"><i class="bi bi-stopwatch-fill"></i></div>
            Pomodoro
        </a>

        <div class="nav-label">Learning</div>

        <a href="{{ route('notes.index') }}" class="{{ request()->routeIs('notes.*') ? 'active' : '' }}">
            <div class="nav-icon"><i class="bi bi-journal-text"></i></div>
            Notes
        </a>
        <a href="{{ route('flashcards.index') }}" class="{{ request()->routeIs('flashcards.*') ? 'active' : '' }}">
            <div class="nav-icon"><i class="bi bi-layers-fill"></i></div>
            Flashcards
        </a>
        <a href="{{ route('exams.index') }}" class="{{ request()->routeIs('exams.*') ? 'active' : '' }}">
            <div class="nav-icon"><i class="bi bi-calendar-event-fill"></i></div>
            Exams
        </a>

        <div class="nav-label">Account</div>

        <a href="{{ route('subjects.index') }}" class="{{ request()->routeIs('subjects.*') ? 'active' : '' }}">
            <div class="nav-icon"><i class="bi bi-book-fill"></i></div>
            Subjects
        </a>
        <a href="/profile" class="{{ request()->is('profile') ? 'active' : '' }}">
            <div class="nav-icon"><i class="bi bi-person-circle"></i></div>
            Profile
        </a>
    </nav>

    {{-- XP Footer --}}
    <div class="sidebar-footer">
        @php $user = auth()->user(); @endphp
        <div class="xp-meta">
            <span class="xp-val"><i class="bi bi-star-fill me-1"></i>{{ number_format($user->xp) }} XP</span>
            <span>Lv.{{ $user->level + 1 }}</span>
        </div>
        <div class="xp-track">
            <div class="xp-fill" style="width:{{ $user->xpProgress() }}%"></div>
        </div>

        @php $streak = $user->streak; @endphp
        @if($streak && $streak->current_streak > 0)
        <div class="streak-badge">
            <span class="flame">&#128293;</span>
            {{ $streak->current_streak }}-day streak
            <span style="margin-left:auto;font-size:.68rem;opacity:.7">best: {{ $streak->longest_streak ?? 0 }}</span>
        </div>
        @endif

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="sidebar-logout">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>
    </div>
</div>

{{-- ── MAIN CONTENT ──────────────────────────────────────────── --}}
<div id="main-content">

    {{-- Topbar --}}
    <div class="topbar">
        <div style="display:flex;align-items:center;gap:.75rem">
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>
            <span class="topbar-title">@yield('page-title', 'Dashboard')</span>
        </div>

        <div class="topbar-right">
            <span class="xp-chip">
                <i class="bi bi-star-fill"></i> {{ number_format(auth()->user()->xp) }} XP
            </span>
            <span class="user-chip d-none d-sm-inline">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="topbar-btn">
                    <i class="bi bi-box-arrow-right"></i>
                    <span class="d-none d-sm-inline">Logout</span>
                </button>
            </form>
        </div>
    </div>

    {{-- Flash messages --}}
    <div class="flash-wrap">
        @if(session('success'))
            <div class="st-alert st-alert-success">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
                <button class="close-btn" onclick="this.parentElement.remove()">&times;</button>
            </div>
        @endif
        @if(session('error'))
            <div class="st-alert st-alert-error">
                <i class="bi bi-exclamation-triangle-fill"></i>
                {{ session('error') }}
                <button class="close-btn" onclick="this.parentElement.remove()">&times;</button>
            </div>
        @endif
        @if($errors->any())
            <div class="st-alert st-alert-error">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div>
                    @foreach($errors->all() as $e)
                        <div>{{ $e }}</div>
                    @endforeach
                </div>
                <button class="close-btn" onclick="this.parentElement.remove()">&times;</button>
            </div>
        @endif
    </div>

    <div class="content-wrap">
        @yield('content')
    </div>
</div>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>

<script>
    // Mobile sidebar toggle
    document.getElementById('sidebarToggle')?.addEventListener('click', () => {
        document.getElementById('sidebar').classList.toggle('show');
    });
    // Auto-dismiss flash after 4 seconds
    setTimeout(() => {
        document.querySelectorAll('.st-alert').forEach(el => el.remove());
    }, 4000);
</script>

@stack('scripts')
</body>
</html>
