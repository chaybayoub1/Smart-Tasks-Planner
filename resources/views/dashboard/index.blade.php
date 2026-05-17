{{-- resources/views/dashboard/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<style>
/* ═══════════════════════════════════════════════════════════════
   DASHBOARD — Smart Tasks Planner
   Aesthetic: refined dark-accent SaaS, geometric precision
═══════════════════════════════════════════════════════════════ */

/* ── Tokens ───────────────────────────────────────────────── */
:root {
    --db-indigo:   #6366f1;
    --db-violet:   #8b5cf6;
    --db-emerald:  #10b981;
    --db-amber:    #f59e0b;
    --db-sky:      #0ea5e9;
    --db-rose:     #f43f5e;
    --db-surface:  #ffffff;
    --db-surface2: #f8f8fc;
    --db-border:   #e8e8f0;
    --db-text:     #1a1a2e;
    --db-muted:    #8888aa;
    --db-radius:   14px;
    --db-shadow:   0 2px 12px rgba(99,102,241,.08), 0 1px 3px rgba(0,0,0,.04);
    --db-shadow-hover: 0 8px 28px rgba(99,102,241,.14), 0 2px 8px rgba(0,0,0,.06);
}

/* ── KPI Cards ────────────────────────────────────────────── */
.kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.kpi-card {
    position: relative;
    background: var(--db-surface);
    border: 1px solid var(--db-border);
    border-radius: var(--db-radius);
    box-shadow: var(--db-shadow);
    overflow: hidden;
    transition: transform .18s ease, box-shadow .18s ease;
}
.kpi-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--db-shadow-hover);
}

.kpi-accent {
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    border-radius: var(--db-radius) var(--db-radius) 0 0;
}

.kpi-inner {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 20px;
}

.kpi-icon-wrap {
    flex-shrink: 0;
    width: 44px; height: 44px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.25rem;
}

.kpi-body { flex: 1; min-width: 0; }
.kpi-label {
    font-size: .72rem;
    font-weight: 600;
    letter-spacing: .06em;
    text-transform: uppercase;
    color: var(--db-muted);
    margin: 0 0 4px;
}
.kpi-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--db-text);
    margin: 0;
    line-height: 1;
    letter-spacing: -.02em;
}
.kpi-sub {
    font-size: .72rem;
    color: var(--db-muted);
    margin: 4px 0 0;
}

.kpi-trend {
    font-size: .7rem;
    font-weight: 700;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2px;
    padding: 4px 8px;
    border-radius: 8px;
    white-space: nowrap;
}
.trend-up   { color: var(--db-emerald); background: #10b98112; }
.trend-down { color: var(--db-rose);    background: #f43f5e12; }

/* ── Chart Cards ──────────────────────────────────────────── */
.chart-card {
    background: var(--db-surface);
    border: 1px solid var(--db-border);
    border-radius: var(--db-radius);
    box-shadow: var(--db-shadow);
    display: flex;
    flex-direction: column;
    transition: box-shadow .18s ease;
}
.chart-card:hover { box-shadow: var(--db-shadow-hover); }

.chart-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px 0;
}
.chart-card-title {
    font-size: .82rem;
    font-weight: 700;
    letter-spacing: .04em;
    text-transform: uppercase;
    color: var(--db-text);
    display: flex;
    align-items: center;
    gap: 8px;
}
.chart-card-title i { color: var(--db-indigo); font-size: 1rem; }

.chart-badge {
    font-size: .68rem;
    font-weight: 600;
    letter-spacing: .05em;
    text-transform: uppercase;
    color: var(--db-muted);
    background: var(--db-surface2);
    border: 1px solid var(--db-border);
    padding: 3px 9px;
    border-radius: 20px;
}

.chart-card-body {
    padding: 16px 20px 20px;
    flex: 1;
    position: relative;
}

/* ── Section Dividers ─────────────────────────────────────── */
.db-section-label {
    font-size: .7rem;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--db-muted);
    margin: 0 0 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.db-section-label::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--db-border);
}

/* ── Donut Stat Labels ────────────────────────────────────── */
.donut-legend {
    display: flex;
    flex-direction: column;
    gap: 10px;
    justify-content: center;
    padding: 8px 0;
}
.donut-legend-item {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: .8rem;
}
.donut-dot {
    width: 10px; height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
}
.donut-legend-label { color: var(--db-muted); flex: 1; }
.donut-legend-val   { font-weight: 700; color: var(--db-text); }

/* ── Notification / Task List Cards ──────────────────────── */
.notif-card, .task-list-card {
    background: var(--db-surface);
    border: 1px solid var(--db-border);
    border-radius: var(--db-radius);
    box-shadow: var(--db-shadow);
    overflow: hidden;
}
.notif-card-header, .task-list-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 20px;
    border-bottom: 1px solid var(--db-border);
    font-size: .8rem;
    font-weight: 700;
    letter-spacing: .04em;
    text-transform: uppercase;
    color: var(--db-text);
}
.notif-card-header i, .task-list-card-header i { color: var(--db-indigo); }

/* ── XP / Level Card ──────────────────────────────────────── */
.xp-card {
    background: linear-gradient(135deg, var(--db-indigo), var(--db-violet));
    border-radius: var(--db-radius);
    padding: 20px;
    color: #fff;
    box-shadow: 0 4px 20px rgba(99,102,241,.3);
}
.xp-bar-track {
    background: rgba(255,255,255,.2);
    border-radius: 99px;
    height: 8px;
    overflow: hidden;
    margin: 10px 0 6px;
}
.xp-bar-fill {
    height: 100%;
    background: #fff;
    border-radius: 99px;
    transition: width .6s ease;
}

/* ── Quick Actions ────────────────────────────────────────── */
.quick-actions-card {
    background: var(--db-surface);
    border: 1px solid var(--db-border);
    border-radius: var(--db-radius);
    box-shadow: var(--db-shadow);
    padding: 20px;
}
.qa-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 9px 18px;
    border-radius: 10px;
    font-size: .82rem;
    font-weight: 600;
    border: 1.5px solid transparent;
    text-decoration: none;
    transition: all .15s ease;
}
.qa-btn-primary {
    background: var(--db-indigo);
    color: #fff;
    border-color: var(--db-indigo);
}
.qa-btn-primary:hover { background: var(--db-violet); border-color: var(--db-violet); color:#fff; }
.qa-btn-outline {
    background: var(--db-surface2);
    color: var(--db-text);
    border-color: var(--db-border);
}
.qa-btn-outline:hover { border-color: var(--db-indigo); color: var(--db-indigo); background:#fff; }

/* ── Badges ───────────────────────────────────────────────── */
.badge-chip {
    display: inline-flex;
    flex-direction: column;
    align-items: center;
    gap: 3px;
    padding: 8px 10px;
    background: var(--db-surface2);
    border: 1px solid var(--db-border);
    border-radius: 10px;
    transition: transform .15s;
}
.badge-chip:hover { transform: scale(1.08); }
.badge-chip-icon { font-size: 1.5rem; line-height: 1; }
.badge-chip-name { font-size: .58rem; color: var(--db-muted); font-weight: 600; letter-spacing: .04em; max-width: 56px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; text-align:center; }

/* ══════════════════════════════════════════════════════════
   TASK STATUS WIDGET  (redesigned — compact KPI rows)
══════════════════════════════════════════════════════════ */
.status-widget {
    background: var(--db-surface);
    border: 1px solid var(--db-border);
    border-radius: var(--db-radius);
    box-shadow: var(--db-shadow);
    overflow: hidden;
    transition: box-shadow .18s ease;
}
.status-widget:hover { box-shadow: var(--db-shadow-hover); }

.status-widget-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 18px;
    border-bottom: 1px solid var(--db-border);
}
.status-widget-title {
    font-size: .78rem;
    font-weight: 700;
    letter-spacing: .05em;
    text-transform: uppercase;
    color: var(--db-text);
    display: flex; align-items: center; gap: 7px;
}
.status-widget-title i { color: var(--db-indigo); }
.status-widget-total {
    font-size: .72rem;
    font-weight: 600;
    color: var(--db-muted);
    background: var(--db-surface2);
    border: 1px solid var(--db-border);
    padding: 3px 10px;
    border-radius: 20px;
}

/* Four metric rows */
.status-row {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 11px 18px;
    border-bottom: 1px solid var(--db-border);
    transition: background .15s;
}
.status-row:last-child { border-bottom: none; }
.status-row:hover { background: var(--db-surface2); }

.status-dot {
    width: 9px; height: 9px;
    border-radius: 50%;
    flex-shrink: 0;
}

.status-label {
    font-size: .8rem;
    font-weight: 500;
    color: var(--db-text);
    flex: 0 0 82px; /* fixed column so bars align */
}

/* Mini progress bar */
.status-bar-wrap {
    flex: 1;
    background: #f0f0f8;
    border-radius: 99px;
    height: 6px;
    overflow: hidden;
}
.status-bar-fill {
    height: 100%;
    border-radius: 99px;
    transition: width .55s cubic-bezier(.4,0,.2,1);
}

.status-count {
    font-size: .78rem;
    font-weight: 700;
    color: var(--db-text);
    flex: 0 0 22px;
    text-align: right;
}

.status-pct {
    font-size: .7rem;
    font-weight: 600;
    flex: 0 0 38px;
    text-align: right;
    color: var(--db-muted);
}

/* Overdue row gets a warning tint */
.status-row.is-overdue {
    background: #fff5f5;
}
.status-row.is-overdue:hover { background: #fee2e2; }
.status-row.is-overdue .status-label { color: var(--db-rose); font-weight: 600; }
.status-row.is-overdue .status-pct   { color: var(--db-rose); }
.overdue-badge {
    font-size: .62rem;
    font-weight: 700;
    letter-spacing: .04em;
    padding: 2px 7px;
    border-radius: 20px;
    background: #f43f5e18;
    color: var(--db-rose);
    border: 1px solid #f43f5e30;
    white-space: nowrap;
}

/* ══════════════════════════════════════════════════════════
   SUBJECT DISTRIBUTION WIDGET  (new)
══════════════════════════════════════════════════════════ */
.subject-dist-widget {
    background: var(--db-surface);
    border: 1px solid var(--db-border);
    border-radius: var(--db-radius);
    box-shadow: var(--db-shadow);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    transition: box-shadow .18s ease;
    flex: 1;
}
.subject-dist-widget:hover { box-shadow: var(--db-shadow-hover); }

.subject-dist-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 18px;
    border-bottom: 1px solid var(--db-border);
}
.subject-dist-title {
    font-size: .78rem;
    font-weight: 700;
    letter-spacing: .05em;
    text-transform: uppercase;
    color: var(--db-text);
    display: flex; align-items: center; gap: 7px;
}
.subject-dist-title i { color: var(--db-indigo); }

/* Top-subject badge */
.top-subject-badge {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: .68rem;
    font-weight: 700;
    letter-spacing: .03em;
    color: var(--db-amber);
    background: #f59e0b10;
    border: 1px solid #f59e0b30;
    padding: 3px 9px;
    border-radius: 20px;
    white-space: nowrap;
    max-width: 130px;
    overflow: hidden;
    text-overflow: ellipsis;
}

.subject-dist-body {
    padding: 14px 18px 18px;
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 14px;
}

/* Donut + legend side-by-side */
.subject-dist-inner {
    display: flex;
    align-items: center;
    gap: 16px;
}
.subject-dist-canvas-wrap {
    flex-shrink: 0;
    width: 110px; height: 110px;
    position: relative;
}
.subject-dist-canvas-wrap canvas {
    width: 100% !important;
    height: 100% !important;
}

/* Subject rows */
.subject-row {
    display: flex;
    align-items: center;
    gap: 9px;
    font-size: .78rem;
    padding: 3px 0;
}
.subject-color-dot {
    width: 9px; height: 9px;
    border-radius: 50%;
    flex-shrink: 0;
}
.subject-name {
    flex: 1;
    color: var(--db-text);
    font-weight: 500;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 90px;
}
.subject-pct-bar-wrap {
    flex: 1;
    background: #f0f0f8;
    border-radius: 99px;
    height: 5px;
    overflow: hidden;
    min-width: 30px;
}
.subject-pct-bar-fill {
    height: 100%;
    border-radius: 99px;
    transition: width .6s cubic-bezier(.4,0,.2,1);
}
.subject-pct-label {
    font-size: .7rem;
    font-weight: 700;
    color: var(--db-muted);
    flex: 0 0 34px;
    text-align: right;
}

/* Empty state */
.subject-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 24px 0;
    color: var(--db-muted);
    text-align: center;
}
.subject-empty i {
    font-size: 2rem;
    opacity: .4;
}
.subject-empty p {
    font-size: .82rem;
    margin: 0;
    line-height: 1.4;
}
</style>
@endpush

@section('content')

{{-- ─── KPI CARDS ─────────────────────────────────────────────────────── --}}
<p class="db-section-label">Overview</p>
<div class="kpi-grid">

    @include('dashboard.partials._kpi_card', ['kpi' => [
        'label' => 'Total Tasks',
        'value' => $taskStats['total'] ?? 0,
        'icon'  => 'check2-square',
        'color' => '#6366f1',
        'sub'   => ($taskStats['completion_rate'] ?? 0) . '% completion rate',
    ]])

    @include('dashboard.partials._kpi_card', ['kpi' => [
        'label' => 'Completed',
        'value' => $taskStats['completed'] ?? 0,
        'icon'  => 'check-circle-fill',
        'color' => '#10b981',
        'sub'   => ($taskStats['pending'] ?? 0) . ' pending · ' . ($taskStats['in_progress'] ?? 0) . ' in progress',
    ]])

    @include('dashboard.partials._kpi_card', ['kpi' => [
        'label' => 'Weekly XP',
        'value' => number_format($studyStats['weekly_xp'] ?? 0),
        'icon'  => 'lightning-fill',
        'color' => '#f59e0b',
        'sub'   => number_format($studyStats['total_xp'] ?? 0) . ' total XP',
    ]])

    @include('dashboard.partials._kpi_card', ['kpi' => [
        'label' => 'Study Time',
        'value' => floor(($studyStats['weekly_study_minutes'] ?? 0) / 60) . 'h ' . (($studyStats['weekly_study_minutes'] ?? 0) % 60) . 'm',
        'icon'  => 'clock-fill',
        'color' => '#0ea5e9',
        'sub'   => 'This week · ' . ($studyStats['weekly_sessions'] ?? 0) . ' sessions',
    ]])

    @include('dashboard.partials._kpi_card', ['kpi' => [
        'label' => 'Day Streak',
        'value' => ($streak->current_streak ?? 0) . ' 🔥',
        'icon'  => 'fire',
        'color' => '#f43f5e',
        'sub'   => 'Best: ' . ($streak->longest_streak ?? 0) . ' days',
    ]])

</div>

{{-- ─── CHARTS ROW ─────────────────────────────────────────────────────── --}}
<p class="db-section-label">Analytics</p>
<div class="row g-3 mb-4">

    {{-- ── LEFT COLUMN: Task Status + Subject Distribution ───────────────── --}}
    <div class="col-12 col-md-4 d-flex flex-column gap-3">

        {{-- Task Status (unchanged) --}}
        <div class="status-widget">
            <div class="status-widget-header">
                <span class="status-widget-title">
                    <i class="bi bi-pie-chart-fill"></i> Task Status
                </span>
                <span class="status-widget-total">
                    {{ $taskStats['total'] ?? 0 }} total
                </span>
            </div>

            {{-- Completed --}}
            <div class="status-row">
                <span class="status-dot" style="background:#10b981"></span>
                <span class="status-label">Completed</span>
                <div class="status-bar-wrap">
                    <div class="status-bar-fill" style="width:{{ $taskStats['completed_percentage'] ?? 0 }}%; background:#10b981;"></div>
                </div>
                <span class="status-count">{{ $taskStats['completed'] ?? 0 }}</span>
                <span class="status-pct">{{ $taskStats['completed_percentage'] ?? 0 }}%</span>
            </div>

            {{-- In Progress --}}
            <div class="status-row">
                <span class="status-dot" style="background:#0ea5e9"></span>
                <span class="status-label">In Progress</span>
                <div class="status-bar-wrap">
                    <div class="status-bar-fill" style="width:{{ $taskStats['in_progress_percentage'] ?? 0 }}%; background:#0ea5e9;"></div>
                </div>
                <span class="status-count">{{ $taskStats['in_progress'] ?? 0 }}</span>
                <span class="status-pct">{{ $taskStats['in_progress_percentage'] ?? 0 }}%</span>
            </div>

            {{-- Pending --}}
            <div class="status-row">
                <span class="status-dot" style="background:#f59e0b"></span>
                <span class="status-label">Pending</span>
                <div class="status-bar-wrap">
                    <div class="status-bar-fill" style="width:{{ $taskStats['pending_percentage'] ?? 0 }}%; background:#f59e0b;"></div>
                </div>
                <span class="status-count">{{ $taskStats['pending'] ?? 0 }}</span>
                <span class="status-pct">{{ $taskStats['pending_percentage'] ?? 0 }}%</span>
            </div>

            {{-- Overdue — warning tint when non-zero --}}
            <div class="status-row {{ ($taskStats['overdue'] ?? 0) > 0 ? 'is-overdue' : '' }}">
                <span class="status-dot" style="background:#f43f5e"></span>
                <span class="status-label">Overdue</span>
                <div class="status-bar-wrap">
                    <div class="status-bar-fill" style="width:{{ $taskStats['overdue_percentage'] ?? 0 }}%; background:#f43f5e;"></div>
                </div>
                <span class="status-count">{{ $taskStats['overdue'] ?? 0 }}</span>
                <span class="status-pct">{{ $taskStats['overdue_percentage'] ?? 0 }}%</span>
            </div>
        </div>

        {{-- ── NEW: Subject Distribution Widget ──────────────────────────── --}}
        <div class="subject-dist-widget">
            <div class="subject-dist-header">
                <span class="subject-dist-title">
                    <i class="bi bi-book-fill"></i> Subject Distribution
                </span>
                @if(!empty($subjectDistribution['top_subject']))
                    <span class="top-subject-badge" title="Most active subject">
                        🏆 {{ $subjectDistribution['top_subject'] }}
                    </span>
                @endif
            </div>

            <div class="subject-dist-body">
                @if(empty($subjectDistribution['labels']))
                    {{-- Empty state --}}
                    <div class="subject-empty">
                        <i class="bi bi-journals"></i>
                        <p>No subject analytics yet.<br>
                           <a href="{{ route('tasks.index') }}" style="color:var(--db-indigo);font-size:.78rem;">Assign subjects to your tasks</a>
                        </p>
                    </div>
                @else
                    {{-- Donut + legend row --}}
                    <div class="subject-dist-inner">
                        <div class="subject-dist-canvas-wrap">
                            <canvas id="subjectDonutChart"></canvas>
                        </div>
                        <div style="flex:1; display:flex; flex-direction:column; gap:7px;">
                            @foreach($subjectDistribution['labels'] as $i => $label)
                            <div class="subject-row">
                                <span class="subject-color-dot" style="background:{{ $subjectDistribution['colors'][$i] }}"></span>
                                <span class="subject-name" title="{{ $label }}">{{ $label }}</span>
                                <div class="subject-pct-bar-wrap">
                                    <div class="subject-pct-bar-fill"
                                         style="width:{{ $subjectDistribution['percentages'][$i] }}%;
                                                background:{{ $subjectDistribution['colors'][$i] }};"></div>
                                </div>
                                <span class="subject-pct-label">{{ $subjectDistribution['percentages'][$i] }}%</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Total task count footnote --}}
                    <p style="margin:0; font-size:.7rem; color:var(--db-muted); text-align:right;">
                        {{ $subjectDistribution['total'] }} task{{ $subjectDistribution['total'] !== 1 ? 's' : '' }} across {{ count($subjectDistribution['labels']) }} subject{{ count($subjectDistribution['labels']) !== 1 ? 's' : '' }}
                    </p>
                @endif
            </div>
        </div>
        {{-- ── END Subject Distribution ────────────────────────────────── --}}

    </div>

    {{-- Bar: Weekly completed tasks --}}
    <div class="col-12 col-md-4">
        @include('dashboard.partials._chart_card', ['chart' => [
            'id'    => 'weeklyBarChart',
            'title' => 'Tasks Done',
            'icon'  => 'bar-chart-fill',
            'badge' => 'Last 7 days',
        ]])
    </div>

    {{-- Line: XP Progression --}}
    <div class="col-12 col-md-4">
        @include('dashboard.partials._chart_card', ['chart' => [
            'id'    => 'xpLineChart',
            'title' => 'XP Progress',
            'icon'  => 'graph-up-arrow',
            'badge' => 'Last 30 days',
        ]])
    </div>

</div>

{{-- ─── TASKS + SIDEBAR ───────────────────────────────────────────────── --}}
<p class="db-section-label">Activity</p>
<div class="row g-3 mb-4">

    {{-- Upcoming Tasks --}}
    <div class="col-md-7">
        <div class="task-list-card h-100">
            <div class="task-list-card-header">
                <span><i class="bi bi-calendar-week me-2"></i>Upcoming Tasks</span>
                <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-outline-secondary py-0" style="font-size:.75rem;">All Tasks</a>
            </div>
            @forelse($upcomingTasks as $task)
            <div class="d-flex align-items-center gap-3 px-4 py-3 border-bottom">
                <form method="POST" action="{{ route('tasks.toggle', $task) }}">
                    @csrf @method('PATCH')
                    <button class="btn btn-sm p-0 border-0 text-{{ $task->status === 'completed' ? 'success' : 'secondary' }}">
                        <i class="bi bi-{{ $task->status === 'completed' ? 'check-circle-fill' : 'circle' }} fs-5"></i>
                    </button>
                </form>
                <div class="flex-grow-1 min-w-0">
                    <div class="fw-600 text-truncate {{ $task->status === 'completed' ? 'text-decoration-line-through text-muted' : '' }}" style="font-size:.88rem;">
                        {{ $task->title }}
                    </div>
                    <div style="font-size:.72rem; color:var(--db-muted);">
                        @if($task->subject)
                            <span class="badge me-1" style="background:{{ $task->subject->color }}20;color:{{ $task->subject->color }};font-size:.65rem;">{{ $task->subject->name }}</span>
                        @endif
                        {{ $task->due_date->format('M d') }} · {{ $task->duration }}min
                    </div>
                </div>
                <span class="badge text-bg-{{ $task->priorityBadgeClass() }}" style="font-size:.65rem;">{{ $task->priority }}</span>
            </div>
            @empty
            <div class="text-center py-5" style="color:var(--db-muted);">
                <i class="bi bi-calendar-check fs-2 d-block mb-2 text-success"></i>
                <span style="font-size:.85rem;">No upcoming tasks. <a href="{{ route('tasks.index') }}">Add one!</a></span>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Notifications + XP + Badges --}}
    <div class="col-md-5 d-flex flex-column gap-3">

        {{-- Notifications --}}
        <div class="notif-card">
            <div class="notif-card-header">
                <span><i class="bi bi-bell-fill me-2"></i>Alerts</span>
                <span style="font-size:.7rem; color:var(--db-muted); font-weight:400; text-transform:none; letter-spacing:0;">
                    <i class="bi bi-journal-text me-1"></i>{{ $totalNotes }} Notes &nbsp;·&nbsp;
                    <i class="bi bi-layers me-1"></i>{{ $totalFlashcards }} Cards
                </span>
            </div>
            <ul class="list-group list-group-flush">
                @if(($taskStats['overdue'] ?? 0) > 0)
                <li class="list-group-item d-flex align-items-center gap-2" style="color:var(--db-rose); font-size:.82rem;">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span>
                        <strong>{{ $taskStats['overdue'] }}</strong> overdue task{{ $taskStats['overdue'] > 1 ? 's' : '' }}
                        <span class="overdue-badge ms-1">{{ $taskStats['overdue_percentage'] ?? 0 }}% of total</span>
                    </span>
                    <a href="{{ route('tasks.index', ['status'=>'pending']) }}" class="ms-auto btn btn-sm btn-outline-danger py-0" style="font-size:.72rem;">View</a>
                </li>
                @endif

                @if($flashcardsDue > 0)
                <li class="list-group-item d-flex align-items-center gap-2" style="color:var(--db-sky); font-size:.82rem;">
                    <i class="bi bi-layers-fill"></i>
                    <span><strong>{{ $flashcardsDue }}</strong> flashcard{{ $flashcardsDue > 1 ? 's' : '' }} due</span>
                    <a href="{{ route('flashcards.review') }}" class="ms-auto btn btn-sm btn-outline-info py-0" style="font-size:.72rem;">Review</a>
                </li>
                @endif

                @foreach($upcomingExams->take(2) as $exam)
                <li class="list-group-item d-flex align-items-center gap-2" style="font-size:.82rem;">
                    <i class="bi bi-calendar-event text-{{ $exam->urgencyClass() }}"></i>
                    <span><strong>{{ $exam->title }}</strong> in {{ $exam->daysUntil() }} day{{ $exam->daysUntil() != 1 ? 's' : '' }}</span>
                </li>
                @endforeach

                @if(($taskStats['overdue'] ?? 0) === 0 && $flashcardsDue === 0 && $upcomingExams->isEmpty())
                <li class="list-group-item text-center py-4" style="color:var(--db-muted); font-size:.83rem;">
                    <i class="bi bi-check-circle-fill text-success fs-3 d-block mb-2"></i>
                    All caught up! 🎉
                </li>
                @endif
            </ul>
        </div>

        {{-- XP / Level --}}
        <div class="xp-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p style="font-size:.68rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; opacity:.7; margin:0 0 2px;">Level Progress</p>
                    <p style="font-size:1.6rem; font-weight:800; margin:0; letter-spacing:-.03em;">Level {{ $user->level }} ⭐</p>
                </div>
                <div style="text-align:right;">
                    <p style="font-size:.75rem; opacity:.8; margin:0;">{{ number_format($user->xp) }} XP</p>
                    <p style="font-size:.68rem; opacity:.6; margin:0;">→ {{ number_format($user->xpForNextLevel()) }} next</p>
                </div>
            </div>
            <div class="xp-bar-track">
                <div class="xp-bar-fill" style="width:{{ $user->xpProgress() }}%"></div>
            </div>
            <p style="font-size:.7rem; opacity:.65; margin:0;">{{ $user->xpProgress() }}% to next level</p>
        </div>

        {{-- Badges --}}
        <div class="notif-card flex-grow-1">
            <div class="notif-card-header">
                <span><i class="bi bi-award-fill me-2"></i>Badges</span>
            </div>
            <div class="p-3">
                @if($badges->isEmpty())
                    <p style="font-size:.8rem; color:var(--db-muted); margin:0;">Complete sessions to earn badges!</p>
                @else
                <div class="d-flex flex-wrap gap-2">
                    @foreach($badges as $badge)
                    <div class="badge-chip" title="{{ $badge->name }}: {{ $badge->description }}" data-bs-toggle="tooltip">
                        <span class="badge-chip-icon">{{ $badge->icon }}</span>
                        <span class="badge-chip-name">{{ $badge->name }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

    </div>
</div>

{{-- ─── QUICK ACTIONS ──────────────────────────────────────────────────── --}}
<div class="quick-actions-card">
    <p class="db-section-label" style="margin-bottom:14px;">Quick Actions</p>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('pomodoro.index') }}" class="qa-btn qa-btn-primary">
            <i class="bi bi-stopwatch-fill"></i> Start Pomodoro
        </a>
        <a href="{{ route('notes.index') }}" class="qa-btn qa-btn-outline">
            <i class="bi bi-plus-lg"></i> New Note
        </a>
        <a href="{{ route('flashcards.review') }}" class="qa-btn qa-btn-outline">
            <i class="bi bi-layers-fill"></i> Review Cards
        </a>
        <a href="{{ route('tasks.index') }}" class="qa-btn qa-btn-outline">
            <i class="bi bi-calendar-plus"></i> Plan Task
        </a>
        <a href="{{ route('exams.index') }}" class="qa-btn qa-btn-outline">
            <i class="bi bi-journal-check"></i> Add Exam
        </a>
    </div>
</div>

@endsection

@push('scripts')
{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>

<script>
/* ────────────────────────────────────────────────────────────
   Shared palette
──────────────────────────────────────────────────────────── */
const COLORS = {
    indigo:  '#6366f1',
    violet:  '#8b5cf6',
    emerald: '#10b981',
    amber:   '#f59e0b',
    sky:     '#0ea5e9',
    rose:    '#f43f5e',
    muted:   '#8888aa',
};

Chart.defaults.font.family = "'Inter', system-ui, sans-serif";
Chart.defaults.color = COLORS.muted;

/* ────────────────────────────────────────────────────────────
   1. DONUT — Task Status (existing, unchanged)
──────────────────────────────────────────────────────────── */
(function () {
    const pending    = {{ $taskStats['pending']    ?? 0 }};
    const inProgress = {{ $taskStats['in_progress'] ?? 0 }};
    const completed  = {{ $taskStats['completed']  ?? 0 }};
    const total      = pending + inProgress + completed;

    const labels = ['Pending', 'In Progress', 'Completed'];
    const data   = [pending, inProgress, completed];
    const colors = [COLORS.amber, COLORS.sky, COLORS.emerald];

    // Build legend
    const legend = document.getElementById('donutLegend');
    if (legend) {
        labels.forEach((lbl, i) => {
            const pct = total > 0 ? Math.round((data[i] / total) * 100) : 0;
            legend.innerHTML += `
                <div class="donut-legend-item">
                    <span class="donut-dot" style="background:${colors[i]}"></span>
                    <span class="donut-legend-label">${lbl}</span>
                    <span class="donut-legend-val">${data[i]} <small style="font-weight:400;color:var(--db-muted)">(${pct}%)</small></span>
                </div>`;
        });
    }
})();

/* ────────────────────────────────────────────────────────────
   2. DONUT — Subject Distribution (NEW)
──────────────────────────────────────────────────────────── */
(function () {
    const canvas = document.getElementById('subjectDonutChart');
    if (!canvas) return; // empty state: canvas not rendered

    const dist   = @json($subjectDistribution);
    if (!dist.labels || dist.labels.length === 0) return;

    new Chart(canvas, {
        type: 'doughnut',
        data: {
            labels: dist.labels,
            datasets: [{
                data:            dist.data,
                backgroundColor: dist.colors,
                borderWidth:     0,
                hoverOffset:     6,
            }]
        },
        options: {
            responsive:    true,
            maintainAspectRatio: true,
            cutout:        '68%',
            animation: {
                animateRotate: true,
                duration:      700,
                easing:        'easeInOutQuart',
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => {
                            const pct = dist.percentages[ctx.dataIndex];
                            return ` ${ctx.label}: ${ctx.raw} tasks (${pct}%)`;
                        }
                    }
                }
            }
        }
    });
})();

/* ────────────────────────────────────────────────────────────
   3. BAR — Weekly Completed Tasks
──────────────────────────────────────────────────────────── */
(function () {
    const chart = @json($weeklyChart);

    // Highlight today (last bar) differently
    const bgColors = chart.data.map((_, i) =>
        i === chart.data.length - 1
            ? COLORS.indigo
            : COLORS.indigo + '55'
    );

    new Chart(document.getElementById('weeklyBarChart'), {
        type: 'bar',
        data: {
            labels: chart.labels,
            datasets: [{
                label: 'Tasks Completed',
                data: chart.data,
                backgroundColor: bgColors,
                borderRadius: 7,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 },
                    grid: { color: 'rgba(0,0,0,.04)' }
                },
                x: { grid: { display: false } }
            }
        }
    });
})();

/* ────────────────────────────────────────────────────────────
   4. LINE — XP Progression (cumulative, 30 days)
──────────────────────────────────────────────────────────── */
(function () {
    const chart = @json($xpChart);

    new Chart(document.getElementById('xpLineChart'), {
        type: 'line',
        data: {
            labels: chart.labels,
            datasets: [{
                label: 'Cumulative XP',
                data: chart.data,
                borderColor: COLORS.violet,
                backgroundColor: COLORS.violet + '18',
                borderWidth: 2.5,
                pointRadius: 0,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: COLORS.violet,
                fill: true,
                tension: 0.4,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,.04)' }
                },
                x: {
                    grid: { display: false },
                    ticks: {
                        maxTicksLimit: 6,
                        maxRotation: 0,
                    }
                }
            }
        }
    });
})();

/* ── Tooltips ─────────────────────────────────────────────── */
document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
    new bootstrap.Tooltip(el);
});
</script>
@endpush
