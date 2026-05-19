{{-- resources/views/statistics/partials/_weekly_comparison.blade.php --}}
@php
    $metrics = [
        ['key' => 'tasks_completed',   'label' => 'Tasks Completed',   'icon' => '✅', 'suffix' => ''],
        ['key' => 'study_minutes',     'label' => 'Study Minutes',      'icon' => '⏱️', 'suffix' => 'min'],
        ['key' => 'xp_earned',         'label' => 'XP Earned',         'icon' => '⚡', 'suffix' => 'xp'],
        ['key' => 'pomodoro_sessions', 'label' => 'Focus Sessions',     'icon' => '🍅', 'suffix' => ''],
    ];
@endphp

<div class="stats-card" style="margin-bottom:1.75rem">

    <div class="stats-section-label">Performance</div>
    <div class="stats-section-title">This Week vs Last Week</div>

    <div class="comparison-grid">
        @foreach($metrics as $m)
            @php
                $d = $weeklyComparison[$m['key']] ?? ['current'=>0,'previous'=>0,'delta'=>0];
                $delta = $d['delta'];
                $cls   = $delta > 0 ? 'delta-pos' : ($delta < 0 ? 'delta-neg' : 'delta-neu');
                $arrow = $delta > 0 ? '↑' : ($delta < 0 ? '↓' : '→');
            @endphp
            <div class="comparison-card">
                <div class="comparison-metric">{{ $m['icon'] }} {{ $m['label'] }}</div>
                <div class="comparison-values">
                    <span class="comparison-current">{{ number_format($d['current']) }}</span>
                    @if($m['suffix'])
                        <span class="comparison-prev">{{ $m['suffix'] }}</span>
                    @endif
                </div>
                <div class="comparison-prev" style="margin-top:4px">
                    vs {{ number_format($d['previous']) }} {{ $m['suffix'] }} last week
                </div>
                <div class="comparison-delta {{ $cls }}">
                    {{ $arrow }} {{ abs($delta) }}%
                </div>
            </div>
        @endforeach
    </div>

</div>
