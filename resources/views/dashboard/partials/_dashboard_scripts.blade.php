{{--
    resources/views/dashboard/partials/_dashboard_scripts.blade.php

    Purpose  : All dashboard-specific JavaScript — Chart.js CDN load +
               four chart initialisers (Task Status donut, Subject
               Distribution donut, Weekly Bar, XP Line) + Bootstrap
               tooltip init.
    Included : Inside @push('scripts') in dashboard/index.blade.php.
    Data     : $taskStats            (array)
               $subjectDistribution  (array) — JSON-serialised
               $weeklyChart          (array) — JSON-serialised
               $xpChart              (array) — JSON-serialised
--}}

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
   1. DONUT — Task Status
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
   2. DONUT — Subject Distribution
──────────────────────────────────────────────────────────── */
(function () {
    const canvas = document.getElementById('subjectDonutChart');
    if (!canvas) return; // empty state: canvas not rendered

    const dist = @json($subjectDistribution);
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

/* ── Bootstrap Tooltips ───────────────────────────────────── */
document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
    new bootstrap.Tooltip(el);
});
</script>
