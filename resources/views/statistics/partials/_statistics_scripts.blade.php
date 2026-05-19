{{-- resources/views/statistics/partials/_statistics_scripts.blade.php --}}
<script>
(function () {
'use strict';

/* ── Design tokens for Chart.js (light theme, matches Bootstrap 5 app) ── */
const C = {
    indigo:   '#6366f1',
    emerald:  '#10b981',
    amber:    '#f59e0b',
    sky:      '#0ea5e9',
    violet:   '#8b5cf6',
    rose:     '#f43f5e',
    text:     '#7c7c9c',
    grid:     'rgba(0,0,0,0.05)',
};

/* ── Shared Chart.js defaults ── */
Chart.defaults.color       = C.text;
Chart.defaults.font.family = "'DM Sans', sans-serif";
Chart.defaults.font.size   = 11;
Chart.defaults.plugins.legend.display = false;

const baseScales = {
    x: {
        grid:  { color: C.grid, drawBorder: false },
        ticks: { color: C.text },
    },
    y: {
        grid:  { color: C.grid, drawBorder: false },
        ticks: { color: C.text },
        beginAtZero: true,
    },
};

/* ── Gradient helper ── */
function linearGrad(ctx, top, bottom) {
    const g = ctx.createLinearGradient(0, 0, 0, ctx.canvas.height);
    g.addColorStop(0, top);
    g.addColorStop(1, bottom);
    return g;
}

/* ==============================================================
   1. SUBJECT COMPLETION CHART (horizontal bar)
   ============================================================== */
@if(!empty($subjectAnalytics['subjects']))
(function () {
    const el = document.getElementById('subjectCompletionChart');
    if (!el) return;
    const ctx = el.getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels:   @json($subjectAnalytics['chart_labels']),
            datasets: [{
                label: 'Completion %',
                data:  @json($subjectAnalytics['chart_rates']),
                backgroundColor: function(context) {
                    const chart = context.chart;
                    const { ctx: c, chartArea } = chart;
                    if (!chartArea) return C.indigo;
                    const g = c.createLinearGradient(chartArea.left, 0, chartArea.right, 0);
                    g.addColorStop(0, C.indigo);
                    g.addColorStop(1, C.violet);
                    return g;
                },
                borderRadius: 5,
                borderSkipped: false,
            }],
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: { callbacks: { label: ctx => ` ${ctx.parsed.x}%` } }
            },
            scales: {
                x: { ...baseScales.x, max: 100, ticks: { callback: v => v + '%', color: C.text } },
                y: { ...baseScales.y, grid: { display: false } },
            },
        },
    });
})();
@endif

/* ==============================================================
   2. FOCUS HOURLY CHART (bar)
   ============================================================== */
@if($focusAnalytics['total_sessions'] > 0)
(function () {
    const el = document.getElementById('focusHourlyChart');
    if (!el) return;
    const ctx  = el.getContext('2d');
    const grad = linearGrad(ctx, 'rgba(99,102,241,0.55)', 'rgba(99,102,241,0.05)');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels:   @json($focusAnalytics['hourly_labels']),
            datasets: [{
                label:           'Sessions',
                data:            @json($focusAnalytics['hourly_data']),
                backgroundColor: grad,
                borderRadius:    4,
                borderSkipped:   false,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: baseScales,
            plugins: { tooltip: { callbacks: { label: ctx => ` ${ctx.parsed.y} session(s)` } } },
        },
    });
})();
@endif

/* ==============================================================
   3. TREND CHARTS — monthly tasks, minutes, xp
   ============================================================== */
@php
    $trendLabels  = json_encode($trends['labels']);
    $trendTasks   = json_encode($trends['tasks']);
    $trendMinutes = json_encode($trends['minutes']);
    $trendXp      = json_encode($trends['xp']);
@endphp

function makeTrendChart(id, labels, data, color, suffix) {
    const el = document.getElementById(id);
    if (!el) return;
    const ctx  = el.getContext('2d');
    const grad = linearGrad(ctx, color + 'aa', color + '11');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                data,
                borderColor:          color,
                backgroundColor:      grad,
                borderWidth:          2,
                pointRadius:          3,
                pointBackgroundColor: color,
                fill:                 true,
                tension:              0.4,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: baseScales,
            plugins: { tooltip: { callbacks: { label: ctx => ` ${ctx.parsed.y}${suffix}` } } },
        },
    });
}

@if(array_sum($trends['tasks']) > 0)
makeTrendChart('trendTasksChart',   {!! $trendLabels !!}, {!! $trendTasks !!},   C.indigo,  ' tasks');
@endif
@if(array_sum($trends['minutes']) > 0)
makeTrendChart('trendMinutesChart', {!! $trendLabels !!}, {!! $trendMinutes !!}, C.emerald, 'min');
@endif
@if(array_sum($trends['xp']) > 0)
makeTrendChart('trendXpChart',      {!! $trendLabels !!}, {!! $trendXp !!},      C.amber,   ' XP');
@endif

/* ==============================================================
   4. XP CUMULATIVE CHART (30-day)
   ============================================================== */
@if(array_sum($xpChart['data']) > 0)
(function () {
    const el = document.getElementById('xpCumulativeChart');
    if (!el) return;
    const ctx  = el.getContext('2d');
    const grad = linearGrad(ctx, 'rgba(245,158,11,0.35)', 'rgba(245,158,11,0.02)');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels:   @json($xpChart['labels']),
            datasets: [{
                data:            @json($xpChart['data']),
                borderColor:     C.amber,
                backgroundColor: grad,
                borderWidth:     2.5,
                pointRadius:     2,
                pointBackgroundColor: C.amber,
                fill:            true,
                tension:         0.4,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: baseScales,
            plugins: { tooltip: { callbacks: { label: ctx => ` ${ctx.parsed.y} XP` } } },
        },
    });
})();
@endif

/* ==============================================================
   5. HEATMAP TOOLTIP (light-theme cards)
   ============================================================== */
(function () {
    const tip = document.getElementById('st-heatmap-tip');
    if (!tip) return;
    document.querySelectorAll('.st-heatmap-cell[data-date]').forEach(cell => {
        cell.addEventListener('mouseenter', () => {
            const count = parseInt(cell.dataset.count, 10);
            tip.textContent = `${cell.dataset.date} — ${count} activit${count === 1 ? 'y' : 'ies'}`;
            tip.style.display = 'block';
        });
        cell.addEventListener('mousemove', e => {
            tip.style.left = (e.clientX + 14) + 'px';
            tip.style.top  = (e.clientY - 32) + 'px';
        });
        cell.addEventListener('mouseleave', () => { tip.style.display = 'none'; });
    });
})();

})();
</script>
