{{--
    resources/views/dashboard/partials/_analytics_row.blade.php

    Purpose  : The full "Analytics" section row containing:
                 - Task Status widget (left column)
                 - Subject Distribution widget (left column)
                 - Weekly Tasks bar chart (centre)
                 - XP Progression line chart (right)
    Included : dashboard/index.blade.php, inside @section('content').
    Data     : $taskStats (array), $subjectDistribution (array)
--}}
<p class="db-section-label">Analytics</p>
<div class="row g-3 mb-4">

    {{-- ── LEFT COLUMN: Task Status + Subject Distribution ───────────────── --}}
    <div class="col-12 col-md-4 d-flex flex-column gap-3">
        @include('dashboard.partials._task_status')
        @include('dashboard.partials._subject_distribution')
    </div>

    {{-- Bar: Weekly completed tasks --}}
    <div class="col-12 col-md-4">
        @include('dashboard.partials._chart_card', ['chart' => [
            'id'    => 'weeklyBarChart',
            'title' => 'Tasks Done',
            'icon'  => 'bar-chart-fill',
            'badge' => 'Last 7 days',
        ]])
    </div>

    {{-- Line: XP Progression --}}
    <div class="col-12 col-md-4">
        @include('dashboard.partials._chart_card', ['chart' => [
            'id'    => 'xpLineChart',
            'title' => 'XP Progress',
            'icon'  => 'graph-up-arrow',
            'badge' => 'Last 30 days',
        ]])
    </div>

</div>
