{{-- resources/views/statistics/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Statistiques')
@section('page-title', '📊 Statistiques & Productivité')

@section('content')

{{-- ════════════════════════════════════════════════════════════════════════════
     PAGE HEADER
════════════════════════════════════════════════════════════════════════════ --}}
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-0" style="color:#1e1b4b">📊 Statistiques & Productivité</h4>
        <p class="text-muted small mb-0">Analyse complète de ton activité académique</p>
    </div>
    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Dashboard
    </a>
</div>

{{-- ════════════════════════════════════════════════════════════════════════════
     ROW 1 — PRIMARY KPI CARDS
════════════════════════════════════════════════════════════════════════════ --}}
<div class="row g-3 mb-4">

    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#6366f1,#4f46e5)">
            <div class="stat-label">Total tâches</div>
            <div class="stat-value">{{ $taskStats['total'] }}</div>
            <div class="mt-1" style="font-size:.78rem;opacity:.85">{{ $taskStats['in_progress'] }} en cours</div>
            <i class="bi bi-list-check stat-icon"></i>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#10b981,#059669)">
            <div class="stat-label">Terminées</div>
            <div class="stat-value">{{ $taskStats['completed'] }}</div>
            <div class="mt-1" style="font-size:.78rem;opacity:.85">{{ $taskStats['overdue'] }} en retard</div>
            <i class="bi bi-check2-all stat-icon"></i>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#3b82f6,#1d4ed8)">
            <div class="stat-label">Taux de complétion</div>
            <div class="stat-value">{{ $taskStats['completion_rate'] }}<span style="font-size:1.1rem">%</span></div>
            <div class="progress mt-2" style="height:5px;background:rgba(255,255,255,.25);border-radius:99px">
                <div class="progress-bar bg-white" style="width:{{ $taskStats['completion_rate'] }}%;border-radius:99px"></div>
            </div>
            <i class="bi bi-bullseye stat-icon"></i>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#f59e0b,#d97706)">
            <div class="stat-label">XP cette semaine</div>
            <div class="stat-value">{{ $studyStats['weekly_xp'] }}</div>
            <div class="mt-1" style="font-size:.78rem;opacity:.85">{{ $studyStats['weekly_sessions'] }} sessions Pomodoro</div>
            <i class="bi bi-lightning-fill stat-icon"></i>
        </div>
    </div>

</div>

{{-- ════════════════════════════════════════════════════════════════════════════
     ROW 2 — SECONDARY METRIC CARDS
════════════════════════════════════════════════════════════════════════════ --}}
<div class="row g-3 mb-4">

    <div class="col-6 col-md-3">
        <div class="card h-100">
            <div class="card-body">
                <p class="text-muted small mb-1"><i class="bi bi-stopwatch text-primary me-1"></i>Étude (semaine)</p>
                <h4 class="fw-bold mb-0 text-primary">{{ $studyStats['weekly_study_minutes'] }}<small class="fw-normal fs-6 text-muted"> min</small></h4>
                <p class="text-muted small mb-0 mt-1">Total : {{ number_format($studyStats['total_study_minutes']) }} min</p>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card h-100">
            <div class="card-body">
                <p class="text-muted small mb-1"><i class="bi bi-graph-up text-info me-1"></i>Moy. par session</p>
                <h4 class="fw-bold mb-0 text-info">{{ $studyStats['avg_session_minutes'] }}<small class="fw-normal fs-6 text-muted"> min</small></h4>
                <p class="text-muted small mb-0 mt-1">{{ $studyStats['total_sessions'] }} sessions au total</p>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card h-100">
            <div class="card-body">
                <p class="text-muted small mb-1"><i class="bi bi-exclamation-triangle text-danger me-1"></i>En retard</p>
                <h4 class="fw-bold mb-0 text-danger">{{ $taskStats['overdue'] }}</h4>
                <p class="text-muted small mb-0 mt-1">{{ $taskStats['overdue_rate'] }}% du total</p>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card h-100">
            <div class="card-body">
                <p class="text-muted small mb-1"><i class="bi bi-star-fill text-warning me-1"></i>XP total</p>
                <h4 class="fw-bold mb-0 text-warning">{{ number_format($studyStats['total_xp']) }}</h4>
                <p class="text-muted small mb-0 mt-1">Niveau {{ auth()->user()->level }}</p>
            </div>
        </div>
    </div>

</div>

{{-- ════════════════════════════════════════════════════════════════════════════
     ROW 3 — DONUT + BAR CHART
════════════════════════════════════════════════════════════════════════════ --}}
<div class="row g-3 mb-4">

    <div class="col-12 col-md-5">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-pie-chart-fill me-1 text-primary"></i> Répartition des tâches
            </div>
            <div class="card-body d-flex align-items-center justify-content-center" style="height:280px">
                <canvas id="donutChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-7">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-bar-chart-fill me-1 text-primary"></i> Tâches terminées — 7 derniers jours
            </div>
            <div class="card-body" style="height:280px">
                <canvas id="weeklyTasksChart"></canvas>
            </div>
        </div>
    </div>

</div>

{{-- ════════════════════════════════════════════════════════════════════════════
     ROW 4 — XP LINE + RADAR
════════════════════════════════════════════════════════════════════════════ --}}
<div class="row g-3 mb-4">

    <div class="col-12 col-md-7">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-graph-up-arrow me-1" style="color:#a855f7"></i> Progression XP — 30 derniers jours
            </div>
            <div class="card-body" style="height:280px">
                <canvas id="xpLineChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-5">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-reception-4 me-1 text-success"></i> Productivité par matière
            </div>
            <div class="card-body d-flex align-items-center justify-content-center" style="height:280px">
                @if(count($subjectChart['labels']) > 0)
                    <canvas id="subjectRadarChart"></canvas>
                @else
                    <p class="text-muted small text-center mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        Aucune donnée par matière pour l'instant.<br>
                        Associe tes tâches et sessions à des matières.
                    </p>
                @endif
            </div>
        </div>
    </div>

</div>

{{-- ════════════════════════════════════════════════════════════════════════════
     ROW 5 — WEEKLY COMPARISON
════════════════════════════════════════════════════════════════════════════ --}}
<h6 class="fw-semibold text-muted text-uppercase mb-3" style="font-size:.72rem;letter-spacing:.08em">
    Semaine courante vs semaine précédente
</h6>
<div class="row g-3 mb-4">
    @php
        $comparisons = [
            ['label' => 'Tâches terminées', 'icon' => 'check2-all',    'key' => 'tasks_completed',   'unit' => '',    'color' => '#10b981'],
            ['label' => "Minutes d'étude",  'icon' => 'stopwatch',      'key' => 'study_minutes',     'unit' => 'min', 'color' => '#6366f1'],
            ['label' => 'XP gagnés',         'icon' => 'lightning-fill', 'key' => 'xp_earned',         'unit' => 'xp',  'color' => '#f59e0b'],
            ['label' => 'Sessions Pomodoro', 'icon' => 'alarm-fill',     'key' => 'pomodoro_sessions', 'unit' => '',    'color' => '#ef4444'],
        ];
    @endphp

    @foreach($comparisons as $c)
        @php
            $metric = $weeklyComparison[$c['key']];
            $up     = $metric['delta'] >= 0;
        @endphp
        <div class="col-6 col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-{{ $c['icon'] }}" style="color:{{ $c['color'] }};font-size:1rem"></i>
                        <span class="text-muted small">{{ $c['label'] }}</span>
                    </div>
                    <h4 class="fw-bold mb-1" style="color:{{ $c['color'] }}">
                        {{ $metric['current'] }}
                        @if($c['unit'])<small class="fw-normal fs-6 text-muted"> {{ $c['unit'] }}</small>@endif
                    </h4>
                    <div class="d-flex align-items-center gap-1">
                        <span class="fw-semibold small {{ $up ? 'text-success' : 'text-danger' }}">
                            {{ $up ? '↑' : '↓' }} {{ abs($metric['delta']) }}%
                        </span>
                        <span class="text-muted" style="font-size:.73rem">vs {{ $metric['previous'] }} {{ $c['unit'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

{{-- ════════════════════════════════════════════════════════════════════════════
     ROW 6 — HEATMAP
════════════════════════════════════════════════════════════════════════════ --}}
<div class="card mb-2">
    <div class="card-header">
        <i class="bi bi-calendar3 me-1 text-primary"></i>
        Activité — 365 jours
        <span class="text-muted small fw-normal ms-2">Sessions Pomodoro complétées par jour</span>
    </div>
    <div class="card-body">
        <div id="heatmap" class="overflow-auto pb-1"></div>
        <div class="d-flex align-items-center gap-2 mt-2">
            <span class="text-muted" style="font-size:.73rem">Moins</span>
            @foreach(['#e2e8f0','#bfdbfe','#93c5fd','#3b82f6','#1d4ed8'] as $col)
                <div style="width:13px;height:13px;background:{{ $col }};border-radius:3px"></div>
            @endforeach
            <span class="text-muted" style="font-size:.73rem">Plus</span>
        </div>
    </div>
</div>

@endsection

{{-- ════════════════════════════════════════════════════════════════════════════
     SCRIPTS
     Note: Chart.js is already loaded globally in layouts/app.blade.php
     We only add the initialisation logic here via @push('scripts')
════════════════════════════════════════════════════════════════════════════ --}}
@push('scripts')
<script>
(function () {
    const C = {
        indigo:  '#6366f1',
        emerald: '#10b981',
        amber:   '#f59e0b',
        sky:     '#0ea5e9',
        purple:  '#a855f7',
    };
    const grid = 'rgba(0,0,0,0.05)';

    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.font.size   = 12;

    // ── 1. Donut ──────────────────────────────────────────────────────────────
    new Chart(document.getElementById('donutChart'), {
        type: 'doughnut',
        data: {
            labels: ['En attente', 'En cours', 'Terminées'],
            datasets: [{
                data: [{{ $taskStats['pending'] }}, {{ $taskStats['in_progress'] }}, {{ $taskStats['completed'] }}],
                backgroundColor: [C.amber, C.sky, C.emerald],
                borderWidth: 0,
                hoverOffset: 10,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { position: 'bottom' },
                tooltip: { callbacks: { label: ctx => `  ${ctx.label} : ${ctx.parsed}` } },
            },
        },
    });

    // ── 2. Bar — 7-day tasks ─────────────────────────────────────────────────
    new Chart(document.getElementById('weeklyTasksChart'), {
        type: 'bar',
        data: {
            labels:   {!! json_encode($weeklyTasksChart['labels']) !!},
            datasets: [{
                label: 'Tâches terminées',
                data:  {!! json_encode($weeklyTasksChart['data']) !!},
                backgroundColor: C.indigo + 'cc',
                borderRadius: 7,
                borderSkipped: false,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false } },
                y: { grid: { color: grid }, beginAtZero: true, ticks: { stepSize: 1 } },
            },
        },
    });

    // ── 3. Line — XP 30 days ────────────────────────────────────────────────
    new Chart(document.getElementById('xpLineChart'), {
        type: 'line',
        data: {
            labels:   {!! json_encode($xpProgressChart['labels']) !!},
            datasets: [{
                label: 'XP cumulé',
                data:  {!! json_encode($xpProgressChart['data']) !!},
                borderColor:          C.purple,
                backgroundColor:      C.purple + '20',
                fill: true,
                tension: 0.4,
                pointRadius: 3,
                pointHoverRadius: 6,
                pointBackgroundColor: C.purple,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, ticks: { maxTicksLimit: 8 } },
                y: { grid: { color: grid }, beginAtZero: true },
            },
        },
    });

    // ── 4. Radar — subjects ───────────────────────────────────────────────────
    @if(count($subjectChart['labels']) > 0)
    new Chart(document.getElementById('subjectRadarChart'), {
        type: 'radar',
        data: {
            labels: {!! json_encode($subjectChart['labels']) !!},
            datasets: [
                {
                    label: 'Tâches terminées',
                    data:  {!! json_encode($subjectChart['tasks']) !!},
                    borderColor: C.emerald, backgroundColor: C.emerald + '33',
                    pointBackgroundColor: C.emerald,
                },
                {
                    label: 'Minutes Pomodoro',
                    data:  {!! json_encode($subjectChart['minutes']) !!},
                    borderColor: C.indigo, backgroundColor: C.indigo + '33',
                    pointBackgroundColor: C.indigo,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } },
            scales: { r: { beginAtZero: true, grid: { color: grid }, ticks: { backdropColor: 'transparent' } } },
        },
    });
    @endif

    // ── 5. Heatmap ───────────────────────────────────────────────────────────
    (function () {
        const raw  = {!! json_encode($heatmapData) !!};
        const el   = document.getElementById('heatmap');
        const CELL = 13, GAP = 2;
        const today = new Date();
        const start = new Date(today);
        start.setDate(start.getDate() - 364);
        const dow = start.getDay();
        start.setDate(start.getDate() - (dow === 0 ? 6 : dow - 1));

        const COLS  = Math.ceil(((today - start) / 86400000 + 1) / 7) + 1;
        const maxV  = Math.max(1, ...Object.values(raw));
        const cols  = ['#e2e8f0','#bfdbfe','#93c5fd','#3b82f6','#1d4ed8'];
        const months= ['Jan','Fév','Mar','Avr','Mai','Jun','Jul','Aoû','Sep','Oct','Nov','Déc'];
        const ns    = 'http://www.w3.org/2000/svg';
        const svg   = document.createElementNS(ns, 'svg');

        svg.setAttribute('width',  COLS * (CELL + GAP));
        svg.setAttribute('height', 7 * (CELL + GAP) + 24);

        let col = 0, row = 0;
        const cur  = new Date(start);
        const seen = new Set();

        while (cur <= today) {
            const key   = cur.toISOString().slice(0, 10);
            const count = raw[key] ?? 0;
            const ci    = count === 0 ? 0 : Math.max(1, Math.ceil((count / maxV) * (cols.length - 1)));

            const m = cur.getMonth();
            if (!seen.has(`${cur.getFullYear()}-${m}`)) {
                seen.add(`${cur.getFullYear()}-${m}`);
                const t = document.createElementNS(ns, 'text');
                t.setAttribute('x', col * (CELL + GAP));
                t.setAttribute('y', 11);
                t.setAttribute('fill', '#94a3b8');
                t.setAttribute('font-size', '9');
                t.textContent = months[m];
                svg.appendChild(t);
            }

            const rect = document.createElementNS(ns, 'rect');
            rect.setAttribute('x',      col * (CELL + GAP));
            rect.setAttribute('y',      row * (CELL + GAP) + 20);
            rect.setAttribute('width',  CELL);
            rect.setAttribute('height', CELL);
            rect.setAttribute('rx',     3);
            rect.setAttribute('fill',   cols[ci]);
            const title = document.createElementNS(ns, 'title');
            title.textContent = `${key} : ${count} session(s)`;
            rect.appendChild(title);
            svg.appendChild(rect);

            row++;
            if (row === 7) { row = 0; col++; }
            cur.setDate(cur.getDate() + 1);
        }

        el.appendChild(svg);
    })();

})();
</script>
@endpush
