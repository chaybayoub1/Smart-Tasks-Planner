{{-- resources/views/statistics/partials/_statistics_kpis.blade.php --}}
<div class="st-kpi-grid">

    <div class="st-kpi c-indigo">
        <div class="st-kpi-top"></div>
        <div class="st-kpi-icon"><i class="bi bi-list-check"></i></div>
        <div class="st-kpi-label kpi-label">Total Tasks</div>
        <div class="st-kpi-value kpi-value">{{ $taskStats['total'] }}</div>
        <div class="st-kpi-sub kpi-sub">{{ $taskStats['in_progress'] }} in progress</div>
    </div>

    <div class="st-kpi c-emerald">
        <div class="st-kpi-top"></div>
        <div class="st-kpi-icon"><i class="bi bi-check2-all"></i></div>
        <div class="st-kpi-label kpi-label">Completed</div>
        <div class="st-kpi-value kpi-value">{{ $taskStats['completed'] }}</div>
        <div class="st-kpi-sub kpi-sub">{{ $taskStats['completion_rate'] }}% rate</div>
    </div>

    <div class="st-kpi c-sky">
        <div class="st-kpi-top"></div>
        <div class="st-kpi-icon"><i class="bi bi-stopwatch-fill"></i></div>
        <div class="st-kpi-label kpi-label">Focus Time</div>
        <div class="st-kpi-value kpi-value">{{ number_format($studyStats['total_study_minutes'] / 60, 1) }}<small>h</small></div>
        <div class="st-kpi-sub kpi-sub">{{ $studyStats['total_sessions'] }} sessions total</div>
    </div>

    <div class="st-kpi c-amber">
        <div class="st-kpi-top"></div>
        <div class="st-kpi-icon"><i class="bi bi-lightning-fill"></i></div>
        <div class="st-kpi-label kpi-label">XP This Week</div>
        <div class="st-kpi-value kpi-value">{{ $studyStats['weekly_xp'] }}</div>
        <div class="st-kpi-sub kpi-sub">{{ number_format($studyStats['total_xp']) }} total XP</div>
    </div>

    <div class="st-kpi c-violet">
        <div class="st-kpi-top"></div>
        <div class="st-kpi-icon"><i class="bi bi-fire"></i></div>
        <div class="st-kpi-label kpi-label">Day Streak</div>
        <div class="st-kpi-value kpi-value">{{ $trends['streak'] }}</div>
        <div class="st-kpi-sub kpi-sub">days in a row</div>
    </div>

    <div class="st-kpi c-indigo">
        <div class="st-kpi-top"></div>
        <div class="st-kpi-icon"><i class="bi bi-alarm-fill"></i></div>
        <div class="st-kpi-label kpi-label">Focus Sessions</div>
        <div class="st-kpi-value kpi-value">{{ $studyStats['total_sessions'] }}</div>
        <div class="st-kpi-sub kpi-sub">avg {{ $studyStats['avg_session_minutes'] }} min each</div>
    </div>

    <div class="st-kpi c-emerald">
        <div class="st-kpi-top"></div>
        <div class="st-kpi-icon"><i class="bi bi-graph-up-arrow"></i></div>
        <div class="st-kpi-label kpi-label">Avg Session</div>
        <div class="st-kpi-value kpi-value">{{ $studyStats['avg_session_minutes'] }}<small>m</small></div>
        <div class="st-kpi-sub kpi-sub">per Pomodoro</div>
    </div>

    <div class="st-kpi {{ $taskStats['overdue'] > 0 ? 'c-rose' : 'c-emerald' }}">
        <div class="st-kpi-top"></div>
        <div class="st-kpi-icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
        <div class="st-kpi-label kpi-label">Overdue</div>
        <div class="st-kpi-value kpi-value">{{ $taskStats['overdue'] }}</div>
        <div class="st-kpi-sub kpi-sub">{{ $taskStats['overdue_rate'] }}% of total</div>
    </div>

</div>
