{{-- resources/views/statistics/partials/_heatmap.blade.php --}}
<div class="stats-card" style="margin-bottom:1.75rem">

    <div class="stats-section-label">Activity</div>
    <div class="stats-section-title">Productivity Heatmap</div>

    @if(empty($heatmap['weeks']))
        <div class="empty-state">
            <div class="empty-state-icon">📅</div>
            <div class="empty-state-text">No activity data yet. Complete tasks or study sessions to see your heatmap.</div>
        </div>
    @else
        <div class="heatmap-wrapper">
            <div class="heatmap-grid" id="heatmap-grid">
                @foreach($heatmap['weeks'] as $week)
                    <div class="heatmap-week">
                        @foreach($week as $cell)
                            @if($cell['future'])
                                <div class="heatmap-cell heat-future"></div>
                            @else
                                <div
                                    class="heatmap-cell heat-{{ $cell['level'] }}"
                                    data-date="{{ $cell['date'] }}"
                                    data-count="{{ $cell['count'] }}"
                                ></div>
                            @endif
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>

        <div class="heatmap-legend">
            <span>Less</span>
            <div class="heatmap-legend-cell heat-0"></div>
            <div class="heatmap-legend-cell heat-1"></div>
            <div class="heatmap-legend-cell heat-2"></div>
            <div class="heatmap-legend-cell heat-3"></div>
            <div class="heatmap-legend-cell heat-4"></div>
            <span>More</span>
        </div>

        <div style="margin-top:1rem;font-size:0.8rem;color:var(--text-muted);">
            {{ array_sum($heatmap['dates']) }} total activities in the last 365 days
        </div>
    @endif

</div>
