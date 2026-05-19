{{-- resources/views/statistics/partials/_trend_charts.blade.php --}}

{{-- Monthly trend charts (3-col) --}}
<div class="st-divider-label">Trends</div>
<div class="st-trend-grid">

    <div class="st-card">
        <div class="st-section-eyebrow" style="margin-bottom:.3rem">Monthly</div>
        <div style="font-family:'Syne',sans-serif;font-size:.95rem;font-weight:700;color:var(--st-text);margin-bottom:.75rem">
            📋 Task Completions
        </div>
        @if(array_sum($trends['tasks']) === 0)
            <div class="st-empty" style="padding:1.5rem 0">
                <div class="st-empty-icon" style="font-size:1.5rem">📋</div>
                <div class="st-empty-text" style="font-size:.78rem">No completed tasks in the last 6 months.</div>
            </div>
        @else
            <div class="st-chart-wrap" style="height:160px">
                <canvas id="trendTasksChart"></canvas>
            </div>
        @endif
    </div>

    <div class="st-card">
        <div class="st-section-eyebrow" style="margin-bottom:.3rem">Monthly</div>
        <div style="font-family:'Syne',sans-serif;font-size:.95rem;font-weight:700;color:var(--st-text);margin-bottom:.75rem">
            ⏱️ Study Time
        </div>
        @if(array_sum($trends['minutes']) === 0)
            <div class="st-empty" style="padding:1.5rem 0">
                <div class="st-empty-icon" style="font-size:1.5rem">⏱️</div>
                <div class="st-empty-text" style="font-size:.78rem">No study sessions in the last 6 months.</div>
            </div>
        @else
            <div class="st-chart-wrap" style="height:160px">
                <canvas id="trendMinutesChart"></canvas>
            </div>
        @endif
    </div>

    <div class="st-card">
        <div class="st-section-eyebrow" style="margin-bottom:.3rem">Monthly</div>
        <div style="font-family:'Syne',sans-serif;font-size:.95rem;font-weight:700;color:var(--st-text);margin-bottom:.75rem">
            ⚡ XP Evolution
        </div>
        @if(array_sum($trends['xp']) === 0)
            <div class="st-empty" style="padding:1.5rem 0">
                <div class="st-empty-icon" style="font-size:1.5rem">⚡</div>
                <div class="st-empty-text" style="font-size:.78rem">No XP earned in the last 6 months.</div>
            </div>
        @else
            <div class="st-chart-wrap" style="height:160px">
                <canvas id="trendXpChart"></canvas>
            </div>
        @endif
    </div>

</div>

{{-- 30-day cumulative XP --}}
<div class="st-card mb-4">
    <div class="st-card-header-row">
        <div>
            <div class="st-section-eyebrow">XP Progress</div>
            <div class="st-section-title mb-0">Cumulative XP — Last 30 Days</div>
        </div>
    </div>
    @if(array_sum($xpChart['data']) === 0)
        <div class="st-empty">
            <div class="st-empty-icon">⚡</div>
            <div class="st-empty-text">Earn XP by completing focus sessions to see your progress curve.</div>
        </div>
    @else
        <div class="st-chart-wrap" style="height:200px">
            <canvas id="xpCumulativeChart"></canvas>
        </div>
    @endif
</div>
