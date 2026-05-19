{{-- resources/views/statistics/partials/_focus_analytics.blade.php --}}
<div class="st-card h-100">
    <div class="st-section-eyebrow">Focus</div>
    <div class="st-section-title">Focus Analytics</div>

    @if($focusAnalytics['total_sessions'] === 0)
        <div class="st-empty">
            <div class="st-empty-icon">🍅</div>
            <div class="st-empty-text">No Pomodoro sessions yet.<br>Start your first focus session to unlock analytics.</div>
        </div>
    @else
        {{-- 4-stat mini-grid --}}
        <div class="st-focus-grid">
            <div class="st-focus-stat">
                <div class="st-focus-val">{{ $focusAnalytics['avg_session'] }}<small style="font-size:.75rem;font-weight:500;color:var(--st-muted)">m</small></div>
                <div class="st-focus-lbl">Avg Session</div>
            </div>
            <div class="st-focus-stat">
                <div class="st-focus-val">{{ $focusAnalytics['best_hour'] }}</div>
                <div class="st-focus-lbl">Peak Hour</div>
            </div>
            <div class="st-focus-stat">
                <div class="st-focus-val">{{ $focusAnalytics['total_sessions'] }}</div>
                <div class="st-focus-lbl">Total Sessions</div>
            </div>
            <div class="st-focus-stat">
                <div class="st-focus-val">{{ $focusAnalytics['consistency'] }}<small style="font-size:.75rem;font-weight:500;color:var(--st-muted)">%</small></div>
                <div class="st-focus-lbl">Consistency</div>
            </div>
        </div>

        {{-- Consistency ring + label --}}
        @php
            $r    = 34;
            $circ = 2 * pi() * $r;
            $dash = ($focusAnalytics['consistency'] / 100) * $circ;
        @endphp
        <div class="st-ring-wrap">
            <svg width="84" height="84" viewBox="0 0 84 84" style="flex-shrink:0">
                <circle cx="42" cy="42" r="{{ $r }}" fill="none" stroke="#f0f0f8" stroke-width="7"/>
                <circle cx="42" cy="42" r="{{ $r }}" fill="none"
                    stroke="#6366f1" stroke-width="7"
                    stroke-dasharray="{{ number_format($dash, 2) }} {{ number_format($circ, 2) }}"
                    stroke-linecap="round"
                    transform="rotate(-90 42 42)"/>
                <text x="42" y="46" text-anchor="middle" fill="#1a1a2e" font-size="12" font-family="Syne,sans-serif" font-weight="800">
                    {{ $focusAnalytics['consistency'] }}%
                </text>
            </svg>
            <div class="st-ring-lbl">
                <strong style="color:var(--st-text)">Focus consistency</strong><br>
                over the last 30 days
            </div>
        </div>

        {{-- Hourly bar chart --}}
        <div class="st-chart-wrap" style="height:120px">
            <canvas id="focusHourlyChart"></canvas>
        </div>
    @endif
</div>
