{{-- resources/views/statistics/partials/_insights.blade.php --}}
<div class="stats-card">

    <div class="stats-section-label">Intelligence</div>
    <div class="stats-section-title">Productivity Insights</div>

    @if(empty($insights))
        <div class="empty-state">
            <div class="empty-state-icon">💡</div>
            <div class="empty-state-text">
                Complete more tasks and study sessions to unlock personalized insights.
            </div>
        </div>
    @else
        <div class="insights-grid">
            @foreach($insights as $insight)
                <div class="insight-card {{ $insight['type'] }}">
                    <span class="insight-icon">{{ $insight['icon'] }}</span>
                    <span class="insight-text">{{ $insight['message'] }}</span>
                </div>
            @endforeach
        </div>
    @endif

</div>
