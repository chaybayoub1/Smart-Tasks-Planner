{{-- resources/views/statistics/partials/_focus_analytics.blade.php --}}
<div class="stats-card">

    <div class="stats-section-label">Focus</div>
    <div class="stats-section-title">Focus Analytics</div>

    @if($focusAnalytics['total_sessions'] === 0)
        <div class="empty-state">
            <div class="empty-state-icon">🍅</div>
            <div class="empty-state-text">No Pomodoro sessions yet. Start your first focus session to unlock analytics.</div>
        </div>
    @else

        <div class="focus-stats-grid">
            <div class="focus-stat">
                <div class="focus-stat-value">{{ $focusAnalytics['avg_session'] }}<small style="font-size:0.8rem">min</small></div>
                <div class="focus-stat-label">Avg Session Length</div>
            </div>
            <div class="focus-stat">
                <div class="focus-stat-value">{{ $focusAnalytics['best_hour'] }}</div>
                <div class="focus-stat-label">Peak Study Hour</div>
            </div>
            <div class="focus-stat">
                <div class="focus-stat-value">{{ $focusAnalytics['total_sessions'] }}</div>
                <div class="focus-stat-label">Total Sessions</div>
            </div>
            <div class="focus-stat">
                <div class="focus-stat-value">{{ $focusAnalytics['consistency'] }}<small style="font-size:0.8rem">%</small></div>
                <div class="focus-stat-label">30-Day Consistency</div>
            </div>
        </div>

        {{-- Consistency ring (SVG) --}}
        @php $r = 36; $circ = 2 * pi() * $r; $dash = ($focusAnalytics['consistency'] / 100) * $circ; @endphp
        <div class="ring-wrap" style="margin-bottom:1rem">
            <svg width="90" height="90" viewBox="0 0 90 90">
                <circle cx="45" cy="45" r="{{ $r }}" fill="none" stroke="rgba(99,102,241,0.1)" stroke-width="7"/>
                <circle cx="45" cy="45" r="{{ $r }}" fill="none"
                    stroke="#6366f1" stroke-width="7"
                    stroke-dasharray="{{ number_format($dash,2) }} {{ number_format($circ,2) }}"
                    stroke-linecap="round"
                    transform="rotate(-90 45 45)"/>
                <text x="45" y="49" text-anchor="middle" fill="#e8eaf6" font-size="13" font-family="Syne,sans-serif" font-weight="700">
                    {{ $focusAnalytics['consistency'] }}%
                </text>
            </svg>
            <div class="ring-label">Focus consistency<br>last 30 days</div>
        </div>

        {{-- Hourly chart --}}
        <div class="chart-container" style="height:130px">
            <canvas id="focusHourlyChart"></canvas>
        </div>

    @endif

</div>
