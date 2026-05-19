{{-- resources/views/statistics/partials/_insights.blade.php --}}
<div class="st-card">
    <div class="st-section-eyebrow">Intelligence</div>
    <div class="st-section-title">Productivity Insights</div>

    @if(empty($insights))
        <div class="st-empty">
            <div class="st-empty-icon">💡</div>
            <div class="st-empty-text">Complete more tasks and study sessions to unlock personalized insights.</div>
        </div>
    @else
        <div class="st-insights-grid">
            @foreach($insights as $insight)
                <div class="st-insight {{ $insight['type'] }}">
                    <span class="st-insight-icon">{{ $insight['icon'] }}</span>
                    <span class="st-insight-text">{{ $insight['message'] }}</span>
                </div>
            @endforeach
        </div>
    @endif
</div>
