{{--
    resources/views/dashboard/partials/_kpi_row.blade.php

    Purpose  : Renders the full KPI cards row ("Overview" section).
    Included : dashboard/index.blade.php, inside @section('content').
    Data     : $taskStats (array), $studyStats (array), $streak (model)
--}}
<p class="db-section-label">Overview</p>
<div class="kpi-grid">

    @include('dashboard.partials._kpi_card', ['kpi' => [
        'label' => 'Total Tasks',
        'value' => $taskStats['total'] ?? 0,
        'icon'  => 'check2-square',
        'color' => '#6366f1',
        'sub'   => ($taskStats['completion_rate'] ?? 0) . '% completion rate',
    ]])

    @include('dashboard.partials._kpi_card', ['kpi' => [
        'label' => 'Completed',
        'value' => $taskStats['completed'] ?? 0,
        'icon'  => 'check-circle-fill',
        'color' => '#10b981',
        'sub'   => ($taskStats['pending'] ?? 0) . ' pending · ' . ($taskStats['in_progress'] ?? 0) . ' in progress',
    ]])

    @include('dashboard.partials._kpi_card', ['kpi' => [
        'label' => 'Weekly XP',
        'value' => number_format($studyStats['weekly_xp'] ?? 0),
        'icon'  => 'lightning-fill',
        'color' => '#f59e0b',
        'sub'   => number_format($studyStats['total_xp'] ?? 0) . ' total XP',
    ]])

    @include('dashboard.partials._kpi_card', ['kpi' => [
        'label' => 'Study Time',
        'value' => floor(($studyStats['weekly_study_minutes'] ?? 0) / 60) . 'h ' . (($studyStats['weekly_study_minutes'] ?? 0) % 60) . 'm',
        'icon'  => 'clock-fill',
        'color' => '#0ea5e9',
        'sub'   => 'This week · ' . ($studyStats['weekly_sessions'] ?? 0) . ' sessions',
    ]])

    @include('dashboard.partials._kpi_card', ['kpi' => [
        'label' => 'Day Streak',
        'value' => ($streak->current_streak ?? 0) . ' 🔥',
        'icon'  => 'fire',
        'color' => '#f43f5e',
        'sub'   => 'Best: ' . ($streak->longest_streak ?? 0) . ' days',
    ]])

</div>
