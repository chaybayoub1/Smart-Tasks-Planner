{{-- resources/views/statistics/partials/_statistics_kpis.blade.php --}}
<div class="stats-kpis">

    <div class="kpi-card accent">
        <span class="kpi-icon">✅</span>
        <div class="kpi-value">{{ $taskStats['completed'] }}</div>
        <div class="kpi-label">Tasks Completed</div>
    </div>

    <div class="kpi-card positive">
        <span class="kpi-icon">⏱️</span>
        <div class="kpi-value">{{ number_format($studyStats['total_study_minutes'] / 60, 1) }}<small style="font-size:1rem">h</small></div>
        <div class="kpi-label">Total Study Time</div>
    </div>

    <div class="kpi-card accent">
        <span class="kpi-icon">⚡</span>
        <div class="kpi-value">{{ number_format($studyStats['total_xp']) }}</div>
        <div class="kpi-label">Total XP Earned</div>
    </div>

    <div class="kpi-card positive">
        <span class="kpi-icon">🎯</span>
        <div class="kpi-value">{{ $taskStats['completion_rate'] }}<small style="font-size:1rem">%</small></div>
        <div class="kpi-label">Completion Rate</div>
    </div>

    <div class="kpi-card warning">
        <span class="kpi-icon">🔥</span>
        <div class="kpi-value">{{ $trends['streak'] }}</div>
        <div class="kpi-label">Day Streak</div>
    </div>

    <div class="kpi-card {{ $taskStats['overdue'] > 0 ? 'danger' : 'positive' }}">
        <span class="kpi-icon">⚠️</span>
        <div class="kpi-value">{{ $taskStats['overdue'] }}</div>
        <div class="kpi-label">Overdue Tasks</div>
    </div>

    <div class="kpi-card accent">
        <span class="kpi-icon">🍅</span>
        <div class="kpi-value">{{ $studyStats['total_sessions'] }}</div>
        <div class="kpi-label">Focus Sessions</div>
    </div>

    <div class="kpi-card positive">
        <span class="kpi-icon">📚</span>
        <div class="kpi-value">{{ count($subjectDist['labels']) }}</div>
        <div class="kpi-label">Active Subjects</div>
    </div>

</div>
