{{-- resources/views/statistics/partials/_heatmap.blade.php --}}
<div class="st-card mb-4">
    <div class="st-card-header-row">
        <div>
            <div class="st-section-eyebrow">Activity</div>
            <div class="st-section-title mb-0">Productivity Heatmap</div>
        </div>
        @if(!empty($heatmap['weeks']))
            <span class="badge rounded-pill" style="background:rgba(99,102,241,.1);color:#6366f1;font-size:.7rem;font-weight:600;padding:4px 10px;">
                {{ array_sum($heatmap['dates']) }} activities · 365 days
            </span>
        @endif
    </div>

    @if(empty($heatmap['weeks']))
        <div class="st-empty">
            <div class="st-empty-icon">📅</div>
            <div class="st-empty-text">No activity data yet.<br>Complete tasks or study sessions to populate your heatmap.</div>
        </div>
    @else
        <div class="st-heatmap-scroll">
            <div class="st-heatmap-grid" id="st-heatmap-grid">
                @foreach($heatmap['weeks'] as $week)
                    <div class="st-heatmap-week">
                        @foreach($week as $cell)
                            @if($cell['future'])
                                <div class="st-heatmap-cell st-heat-future"></div>
                            @else
                                <div
                                    class="st-heatmap-cell st-heat-{{ $cell['level'] }}"
                                    data-date="{{ $cell['date'] }}"
                                    data-count="{{ $cell['count'] }}"
                                ></div>
                            @endif
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>

        <div class="st-heatmap-legend">
            <span>Less</span>
            <div class="st-heatmap-legend-cell st-heat-0"></div>
            <div class="st-heatmap-legend-cell st-heat-1"></div>
            <div class="st-heatmap-legend-cell st-heat-2"></div>
            <div class="st-heatmap-legend-cell st-heat-3"></div>
            <div class="st-heatmap-legend-cell st-heat-4"></div>
            <span>More</span>
        </div>
    @endif
</div>
