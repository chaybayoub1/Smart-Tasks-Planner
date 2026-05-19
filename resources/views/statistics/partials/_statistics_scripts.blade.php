{{-- resources/views/statistics/partials/_statistics_scripts.blade.php --}}
<script>
(function () {
'use strict';

/* ── Design tokens for Chart.js ── */
const C = {
    accent:   '#6366f1',
    positive: '#10b981',
    warning:  '#f59e0b',
    danger:   '#f43f5e',
    violet:   '#8b5cf6',
    text:     '#8b92b8',
    grid:     'rgba(99,102,241,0.08)',
    bg:       '#13162b',
};

/* ── Shared Chart.js defaults ── */
Chart.defaults.color          = C.text;
Chart.defaults.font.family    = "'DM Sans', sans-serif";
Chart.defaults.font.size      = 11;
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
    g.addColorStop(0,   top);
    g.addColorStop(1,   bottom);
    return g;
}

/* ==============================================================
   1. SUBJECT COMPLETION CHART (horizontal bar)
   ============================================================== */
@if(!empty($subjectAnalytics['subjects']))
(function () {
    const el  = document.getElementById('subjectCompletionChart');
    if (!el) return;
    const ctx = el.getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels:   @json($subjectAnalytics['chart_labels']),
            datasets: [{
                label: 'Completion %',
                data:  @json($subjectAnalytics['chart_rates']),
                backgroundColor: ctx => {
                    const g = ctx.chart.ctx.createLinearGradient(0,0,ctx.chart.width,0);
                    g.addColorStop(0, C.accent);
                    g.addColorStop(1, C.positive);
                    return g;
                },
                borderRadius: 6,
                borderSkipped: false,
            }],
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { tooltip: {
                callbacks: { label: ctx => ` ${ctx.parsed.x}%` }
            }},
            scales: {
                x: { ...baseScales.x, max: 100, ticks: { callback: v => v+'%', color: C.text } },
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
    const el  = document.getElementById('focusHourlyChart');
    if (!el) return;
    const ctx = el.getContext('2d');
    const grad = linearGrad(ctx, 'rgba(99,102,241,0.7)', 'rgba(99,102,241,0.05)');
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
            plugins: { tooltip: {
                callbacks: { label: ctx => ` ${ctx.parsed.y} session(s)` }
            }},
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
    const grad = linearGrad(ctx, color + 'bb', color + '11');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                data,
                borderColor:     color,
                backgroundColor: grad,
                borderWidth:     2,
                pointRadius:     3,
                pointBackgroundColor: color,
                fill:            true,
                tension:         0.4,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: baseScales,
            plugins: { tooltip: {
                callbacks: { label: ctx => ` ${ctx.parsed.y}${suffix}` }
            }},
        },
    });
}

@if(array_sum($trends['tasks']) > 0)
makeTrendChart('trendTasksChart',   {!! $trendLabels !!}, {!! $trendTasks !!},   C.accent,   ' tasks');
@endif
@if(array_sum($trends['minutes']) > 0)
makeTrendChart('trendMinutesChart', {!! $trendLabels !!}, {!! $trendMinutes !!}, C.positive, 'min');
@endif
@if(array_sum($trends['xp']) > 0)
makeTrendChart('trendXpChart',      {!! $trendLabels !!}, {!! $trendXp !!},      C.warning,  ' XP');
@endif

/* ==============================================================
   4. XP CUMULATIVE CHART (30-day)
   ============================================================== */
@if(array_sum($xpChart['data']) > 0)
(function () {
    const el  = document.getElementById('xpCumulativeChart');
    if (!el) return;
    const ctx  = el.getContext('2d');
    const grad = linearGrad(ctx, 'rgba(245,158,11,0.5)', 'rgba(245,158,11,0.02)');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels:   @json($xpChart['labels']),
            datasets: [{
                data:            @json($xpChart['data']),
                borderColor:     C.warning,
                backgroundColor: grad,
                borderWidth:     2.5,
                pointRadius:     2,
                pointBackgroundColor: C.warning,
                fill:            true,
                tension:         0.4,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: baseScales,
            plugins: { tooltip: {
                callbacks: { label: ctx => ` ${ctx.parsed.y} XP` }
            }},
        },
    });
})();
@endif

/* ==============================================================
   5. HEATMAP TOOLTIP
   ============================================================== */
(function () {
    const tooltip = document.getElementById('heatmap-tooltip');
    if (!tooltip) return;

    document.querySelectorAll('.heatmap-cell[data-date]').forEach(cell => {
        cell.addEventListener('mouseenter', e => {
            const date  = cell.dataset.date;
            const count = parseInt(cell.dataset.count, 10);
            tooltip.textContent = `${date} — ${count} activit${count === 1 ? 'y' : 'ies'}`;
            tooltip.style.display = 'block';
        });
        cell.addEventListener('mousemove', e => {
            tooltip.style.left = (e.clientX + 12) + 'px';
            tooltip.style.top  = (e.clientY - 28) + 'px';
        });
        cell.addEventListener('mouseleave', () => {
            tooltip.style.display = 'none';
        });
    });
})();

})();
</script>
