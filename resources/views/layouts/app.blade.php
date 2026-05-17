{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SmarTasker') — SmarTasker</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --sf-primary:   #6366f1;
            --sf-primary-d: #4f46e5;
            --sf-sidebar:   #1e1b4b;
            --sf-sidebar-t: rgba(255,255,255,.08);
            --sf-accent:    #f59e0b;
        }
        * { font-family: 'Inter', sans-serif; }

        /* ── Sidebar ────────────────────────────────── */
        #sidebar {
            width: 250px;
            height: 100vh;
            background: var(--sf-sidebar);
            position: fixed; top:0; left:0; z-index:100;
            display: flex; flex-direction: column;
            transition: transform .25s ease;
        }

        /* Brand strip — pinned at top, never scrolls */
        #sidebar .sidebar-brand {
            flex-shrink: 0;
            padding: 1.25rem 1.5rem;
            color: #fff; font-size: 1.3rem; font-weight: 700;
            border-bottom: 1px solid rgba(255,255,255,.1);
            text-decoration: none; display: flex; align-items: center; gap:.6rem;
        }
        #sidebar .sidebar-brand span.badge-lvl {
            font-size:.65rem; background: var(--sf-accent);
            color:#000; border-radius:20px; padding:2px 8px;
        }

        /* Scrollable nav zone */
        #sidebar .sidebar-scroll {
            flex: 1 1 0;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        #sidebar .sidebar-scroll::-webkit-scrollbar { display: none; }

        #sidebar nav a {
            display: flex; align-items: center; gap:.75rem;
            padding: .65rem 1.5rem; color: rgba(255,255,255,.7);
            text-decoration: none; font-size: .9rem; font-weight: 500;
            border-radius: 0; transition: all .15s;
        }
        #sidebar nav a:hover, #sidebar nav a.active {
            color: #fff; background: var(--sf-sidebar-t);
            border-left: 3px solid var(--sf-primary);
            padding-left: calc(1.5rem - 3px);
        }
        #sidebar nav a i { font-size:1.1rem; width:20px; text-align:center; }

        /* Footer — pinned at bottom, never scrolls */
        #sidebar .sidebar-footer {
            flex-shrink: 0;
            padding: .875rem 1rem 1rem;
            border-top: 1px solid rgba(255,255,255,.1);
        }

        /* XP bar (kept above insight widget) */
        #sidebar .xp-strip {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 5px;
        }
        #sidebar .xp-bar { height:5px; background:rgba(255,255,255,.15); border-radius:99px; overflow:hidden; margin-bottom:10px; }
        #sidebar .xp-bar-fill { height:100%; background: var(--sf-primary); border-radius:99px; transition: width .4s ease; }

        /* ── Smart Insight Widget ───────────────────── */
        .insight-widget {
            border-radius: 10px;
            padding: 11px 13px;
            position: relative;
            overflow: hidden;
            transition: box-shadow .2s ease, transform .2s ease;
            cursor: default;
        }
        .insight-widget:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(0,0,0,.25);
        }

        /* Tone variants */
        .insight-widget.tone-positive {
            background: linear-gradient(135deg, rgba(16,185,129,.18) 0%, rgba(99,102,241,.22) 100%);
            border: 1px solid rgba(16,185,129,.3);
        }
        .insight-widget.tone-warning {
            background: linear-gradient(135deg, rgba(244,63,94,.18) 0%, rgba(245,158,11,.18) 100%);
            border: 1px solid rgba(244,63,94,.3);
        }
        .insight-widget.tone-neutral {
            background: linear-gradient(135deg, rgba(99,102,241,.15) 0%, rgba(139,92,246,.15) 100%);
            border: 1px solid rgba(139,92,246,.25);
        }

        /* Subtle animated glow on the top edge */
        .insight-widget::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            border-radius: 10px 10px 0 0;
            animation: insightGlow 3s ease-in-out infinite;
        }
        .tone-positive::before { background: linear-gradient(90deg, #10b981, #6366f1, #10b981); background-size: 200%; }
        .tone-warning::before  { background: linear-gradient(90deg, #f43f5e, #f59e0b, #f43f5e); background-size: 200%; }
        .tone-neutral::before  { background: linear-gradient(90deg, #6366f1, #8b5cf6, #6366f1); background-size: 200%; }

        @keyframes insightGlow {
            0%   { background-position: 0%   50%; opacity: .7; }
            50%  { background-position: 100% 50%; opacity: 1; }
            100% { background-position: 0%   50%; opacity: .7; }
        }

        .insight-label {
            font-size: .62rem;
            font-weight: 700;
            letter-spacing: .09em;
            text-transform: uppercase;
            color: rgba(255,255,255,.45);
            margin: 0 0 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        /* Pulse dot next to label */
        .insight-pulse {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: currentColor;
            opacity: .8;
            animation: dotPulse 2s ease-in-out infinite;
            flex-shrink: 0;
        }
        @keyframes dotPulse {
            0%,100% { transform: scale(1);   opacity: .8; }
            50%      { transform: scale(1.5); opacity: .4; }
        }

        .insight-emoji {
            font-size: 1.1rem;
            line-height: 1;
            display: block;
            margin-bottom: 4px;
        }

        .insight-message {
            font-size: .76rem;
            font-weight: 500;
            color: rgba(255,255,255,.88);
            line-height: 1.45;
            margin: 0;
        }

        .insight-streak {
            margin-top: 7px;
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: .68rem;
            font-weight: 600;
            color: rgba(255,255,255,.55);
        }
        .streak-fire { animation: pulse 1.5s infinite; display: inline-block; }
        @keyframes pulse { 0%,100%{transform:scale(1)} 50%{transform:scale(1.15)} }

        /* ── Main Content ───────────────────────────── */
        #main-content {
            margin-left: 250px;
            min-height: 100vh;
            background: #f8f9ff;
        }
        .topbar {
            background: #fff; border-bottom: 1px solid #e9ecef;
            padding: .75rem 1.5rem;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top:0; z-index:99;
        }

        /* ── Topbar logout button ───────────────────── */
        .topbar-logout-btn {
            display: inline-flex; align-items: center; gap: .4rem;
            padding: .35rem .75rem;
            font-size: .8rem; font-weight: 600;
            color: #6b7280; background: #fff;
            border: 1px solid #e5e7eb; border-radius: 8px;
            cursor: pointer; transition: all .15s ease;
            text-decoration: none; line-height: 1;
        }
        .topbar-logout-btn:hover { color:#ef4444; background:#fef2f2; border-color:#fecaca; }
        .topbar-logout-btn i { font-size: .9rem; }

        /* ── Cards ──────────────────────────────────── */
        .card { border:none; border-radius:12px; box-shadow: 0 1px 8px rgba(0,0,0,.06); }
        .card-header { background: transparent; border-bottom: 1px solid rgba(0,0,0,.06); font-weight:600; }

        /* ── Stat cards ─────────────────────────────── */
        .stat-card { border-radius:14px; padding:1.25rem 1.5rem; color:#fff; position:relative; overflow:hidden; }
        .stat-card .stat-icon { font-size:2.5rem; opacity:.25; position:absolute; right:1rem; bottom:.5rem; }
        .stat-card .stat-value { font-size:2rem; font-weight:700; line-height:1; }
        .stat-card .stat-label { font-size:.8rem; opacity:.85; margin-top:.25rem; }

        /* ── Pomodoro timer ─────────────────────────── */
        #timer-display {
            font-size: 5rem; font-weight:700; letter-spacing:-.05em;
            color: var(--sf-primary); line-height:1;
        }
        .timer-ring { transition: stroke-dashoffset .5s linear; }

        /* ── Flashcard flip ─────────────────────────── */
        .flip-card { perspective:1000px; cursor:pointer; }
        .flip-card-inner { transition: transform .5s; transform-style: preserve-3d; position:relative; }
        .flip-card.flipped .flip-card-inner { transform: rotateY(180deg); }
        .flip-card-front, .flip-card-back {
            backface-visibility: hidden; border-radius:14px;
            padding: 2.5rem; min-height:220px;
            display:flex; align-items:center; justify-content:center; text-align:center;
        }
        .flip-card-back { transform: rotateY(180deg); position:absolute; top:0; left:0; width:100%; }

        /* ── Misc ───────────────────────────────────── */
        .nav-section-label {
            padding: .4rem 1.5rem; font-size:.7rem; font-weight:600;
            text-transform:uppercase; letter-spacing:.1em; color:rgba(255,255,255,.35);
            margin-top:.5rem;
        }

        @media (max-width:768px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.show { transform: translateX(0); }
            #main-content { margin-left:0; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- ── SIDEBAR ──────────────────────────────────────────────── --}}
<div id="sidebar">

    {{-- Brand — pinned, never scrolls --}}
    <a class="sidebar-brand" href="{{ route('dashboard') }}">
        🎓 SmarTasker
        <span class="badge-lvl">Lv.{{ auth()->user()->level }}</span>
    </a>

    {{-- Scrollable nav zone --}}
    <div class="sidebar-scroll">
        <nav class="mt-1">
            <div class="nav-section-label">Main</div>
            <a href="{{ route('dashboard') }}"         class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>
            <a href="{{ route('tasks.index') }}"       class="{{ request()->routeIs('tasks.*') ? 'active' : '' }}">
                <i class="bi bi-check2-square"></i> Study Planner
            </a>
            <a href="{{ route('pomodoro.index') }}"    class="{{ request()->routeIs('pomodoro.*') ? 'active' : '' }}">
                <i class="bi bi-stopwatch-fill"></i> Pomodoro
            </a>
            <a href="{{ route('statistics.index') }}"  class="{{ request()->routeIs('statistics.*') ? 'active' : '' }}">
                <i class="bi bi-graph-up-arrow"></i> Statistiques
            </a>

            <div class="nav-section-label">Learning</div>
            <a href="{{ route('notes.index') }}"       class="{{ request()->routeIs('notes.*') ? 'active' : '' }}">
                <i class="bi bi-journal-text"></i> Notes
            </a>
            <a href="{{ route('flashcards.index') }}"  class="{{ request()->routeIs('flashcards.*') ? 'active' : '' }}">
                <i class="bi bi-layers-fill"></i> Flashcards
            </a>
            <a href="{{ route('exams.index') }}"       class="{{ request()->routeIs('exams.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-event-fill"></i> Exams
            </a>

            <div class="nav-section-label">Settings</div>
            <a href="{{ route('subjects.index') }}"    class="{{ request()->routeIs('subjects.*') ? 'active' : '' }}">
                <i class="bi bi-book-fill"></i> Subjects
            </a>
            <a href="/profile" class="{{ request()->is('profile') ? 'active' : '' }}">
                <i class="bi bi-person-circle"></i> Profile
            </a>
        </nav>
    </div>{{-- /sidebar-scroll --}}

    {{-- ── Footer — pinned at bottom ──────────────────────── --}}
    <div class="sidebar-footer">
        @php $user = auth()->user(); @endphp

        {{-- XP progress strip --}}
        <div class="xp-strip">
            <small class="text-white-50" style="font-size:.72rem">
                <i class="bi bi-star-fill text-warning"></i>
                {{ number_format($user->xp) }} XP
            </small>
            <small class="text-white-50" style="font-size:.72rem">→ Lv.{{ $user->level + 1 }}</small>
        </div>
        <div class="xp-bar">
            <div class="xp-bar-fill" style="width:{{ $user->xpProgress() }}%"></div>
        </div>

        {{-- ── Smart Insight Widget ──────────────────────── --}}
        {{--
            $smartInsight is passed by DashboardController.
            On non-dashboard pages it won't be set, so we fall back to a
            safe default so the layout never throws an undefined-variable error.
        --}}
        @php
            $insight      = $smartInsight ?? ['emoji' => '💡', 'message' => 'Keep studying — every session counts.', 'tone' => 'neutral'];
            $insightTone  = $insight['tone'] ?? 'neutral';
            $insightEmoji = $insight['emoji'] ?? '💡';
            $insightMsg   = $insight['message'] ?? '';
            $streak       = $user->streak;
            $streakCount  = $streak->current_streak ?? 0;
        @endphp

        <div class="insight-widget tone-{{ $insightTone }}">
            {{-- Header label with animated pulse dot --}}
            <p class="insight-label">
                <span class="insight-pulse"></span>
                Smart Insight
            </p>

            {{-- Emoji + message --}}
            <span class="insight-emoji">{{ $insightEmoji }}</span>
            <p class="insight-message">{{ $insightMsg }}</p>

            {{-- Streak footer — only shown when streak > 0 --}}
            @if($streakCount > 0)
            <div class="insight-streak">
                <span class="streak-fire">🔥</span>
                {{ $streakCount }}-day streak
            </div>
            @endif
        </div>
        {{-- /Smart Insight Widget --}}

    </div>
    {{-- /sidebar-footer --}}

</div>
{{-- /sidebar --}}

{{-- ── MAIN CONTENT ──────────────────────────────────────────── --}}
<div id="main-content">
    {{-- Topbar --}}
    <div class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm btn-light d-md-none" id="sidebarToggle">
                <i class="bi bi-list fs-5"></i>
            </button>
            <h6 class="mb-0 fw-600 text-muted">@yield('page-title', 'Dashboard')</h6>
        </div>

        {{-- RIGHT SIDE: XP badge + user name + logout button (only logout kept here) --}}
        <div class="d-flex align-items-center gap-2">
            <span class="badge text-bg-warning fw-semibold">
                <i class="bi bi-star-fill"></i> {{ number_format(auth()->user()->xp) }} XP
            </span>
            <span class="d-none d-sm-inline text-muted small fw-500">
                {{ auth()->user()->name }}
            </span>

            {{-- ✅ Single logout button — lives ONLY here in the topbar --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="topbar-logout-btn">
                    <i class="bi bi-box-arrow-right"></i>
                    <span class="d-none d-sm-inline">Logout</span>
                </button>
            </form>
        </div>
    </div>

    {{-- Flash messages --}}
    <div class="px-4 pt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show py-2" role="alert">
                <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show py-2">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <div class="p-4">
        @yield('content')
    </div>
</div>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>

<script>
    document.getElementById('sidebarToggle')?.addEventListener('click', () => {
        document.getElementById('sidebar').classList.toggle('show');
    });
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(el => {
            bootstrap.Alert.getOrCreateInstance(el)?.close();
        });
    }, 4000);
</script>

@stack('scripts')
</body>
</html>
