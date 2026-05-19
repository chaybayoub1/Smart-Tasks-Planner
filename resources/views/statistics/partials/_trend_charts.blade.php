{{-- resources/views/statistics/partials/_trend_charts.blade.php --}}
<div class="stats-section-label" style="margin-bottom:.5rem">Trends</div>
<div class="trend-charts-grid">

    {{-- Monthly Tasks --}}
    <div class="stats-card">
        <div class="stats-section-title" style="font-size:1rem;margin-bottom:.75rem">📋 Monthly Tasks</div>
        @if(array_sum($trends['tasks']) === 0)
            <div class="empty-state" style="padding:1.5rem 0">
                <div class="empty-state-icon" style="font-size:1.5rem">📋</div>
                <div class="empty-state-text">No completed tasks in the last 6 months.</div>
            </div>
        @else
            <div class="chart-container" style="height:160px">
                <canvas id="trendTasksChart"></canvas>
            </div>
        @endif
    </div>

    {{-- Monthly Study Time --}}
    <div class="stats-card">
        <div class="stats-section-title" style="font-size:1rem;margin-bottom:.75rem">⏱️ Study Time</div>
        @if(array_sum($trends['minutes']) === 0)
            <div class="empty-state" style="padding:1.5rem 0">
                <div class="empty-state-icon" style="font-size:1.5rem">⏱️</div>
                <div class="empty-state-text">No study sessions in the last 6 months.</div>
            </div>
        @else
            <div class="chart-container" style="height:160px">
                <canvas id="trendMinutesChart"></canvas>
            </div>
        @endif
    </div>

    {{-- XP Evolution --}}
    <div class="stats-card">
        <div class="stats-section-title" style="font-size:1rem;margin-bottom:.75rem">⚡ XP Evolution</div>
        @if(array_sum($trends['xp']) === 0)
            <div class="empty-state" style="padding:1.5rem 0">
                <div class="empty-state-icon" style="font-size:1.5rem">⚡</div>
                <div class="empty-state-text">No XP earned in the last 6 months.</div>
            </div>
        @else
            <div class="chart-container" style="height:160px">
                <canvas id="trendXpChart"></canvas>
            </div>
        @endif
    </div>

</div>

{{-- XP 30-day cumulative --}}
<div class="stats-card" style="margin-bottom:1.75rem">
    <div class="stats-section-label">XP Progress</div>
    <div class="stats-section-title">Cumulative XP — Last 30 Days</div>
    @if(array_sum($xpChart['data']) === 0)
        <div class="empty-state">
            <div class="empty-state-icon">⚡</div>
            <div class="empty-state-text">Earn XP by completing focus sessions to see your XP curve.</div>
        </div>
    @else
        <div class="chart-container" style="height:200px">
            <canvas id="xpCumulativeChart"></canvas>
        </div>
    @endif
</div>
