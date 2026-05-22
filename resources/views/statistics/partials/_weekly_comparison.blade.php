{{-- resources/views/statistics/partials/_weekly_comparison.blade.php --}}
@php
    $metrics = [
        ['key' => 'tasks_completed',   'label' => 'Tasks Completed',  'icon' => 'bi-check2-all',    'unit' => '',    'color' => '#10b981'],
        ['key' => 'study_minutes',     'label' => 'Study Minutes',    'icon' => 'bi-stopwatch-fill', 'unit' => 'min', 'color' => '#6366f1'],
        ['key' => 'xp_earned',         'label' => 'XP Earned',        'icon' => 'bi-lightning-fill', 'unit' => 'xp',  'color' => '#f59e0b'],
        ['key' => 'pomodoro_sessions', 'label' => 'Focus Sessions',   'icon' => 'bi-alarm-fill',     'unit' => '',    'color' => '#0ea5e9'],
    ];
@endphp

<div class="st-card mb-4">
    <div class="st-card-header-row">
        <div>
            <div class="st-section-eyebrow">Performance</div>
            <div class="st-section-title mb-0">This Week vs Last Week</div>
        </div>
    </div>

    <div class="st-compare-grid">
        @foreach($metrics as $m)
            @php
                $d     = $weeklyComparison[$m['key']] ?? ['current' => 0, 'previous' => 0, 'delta' => 0];
                $delta = $d['delta'];
                $cls   = $delta > 0 ? 'pos' : ($delta < 0 ? 'neg' : 'neu');
                $arrow = $delta > 0 ? '↑' : ($delta < 0 ? '↓' : '→');
            @endphp
            <div class="st-compare-item">
                <div class="st-compare-metric">
                    <i class="bi {{ $m['icon'] }}" style="color:{{ $m['color'] }}"></i>
                    {{ $m['label'] }}
                </div>
                <div class="st-compare-val kpi-value">
                    {{ number_format($d['current']) }}
                    @if($m['unit'])
                        <small>{{ $m['unit'] }}</small>
                    @endif
                </div>
                <div class="st-compare-prev">vs {{ number_format($d['previous']) }}{{ $m['unit'] ? ' '.$m['unit'] : '' }} last week</div>
                <div class="st-delta {{ $cls }}">
                    {{ $arrow }} {{ abs($delta) }}%
                </div>
            </div>
        @endforeach
    </div>
</div>
