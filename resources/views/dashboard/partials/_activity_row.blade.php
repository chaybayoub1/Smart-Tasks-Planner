{{--
    resources/views/dashboard/partials/_activity_row.blade.php

    Purpose  : The "Activity" section — two-column layout:
                 Left  (col-md-7): Upcoming Tasks list
                 Right (col-md-5): Alerts / XP / Badges sidebar
    Included : dashboard/index.blade.php, inside @section('content').
    Data     : Passed through to child partials — see each child for
               the exact variables required.
--}}
<p class="db-section-label">Activity</p>
<div class="row g-3 mb-4">

    {{-- Upcoming Tasks --}}
    <div class="col-md-7">
        @include('dashboard.partials._upcoming_tasks')
    </div>

    {{-- Alerts + XP + Badges sidebar --}}
    <div class="col-md-5 d-flex flex-column gap-3">
        @include('dashboard.partials._sidebar_insight')
    </div>

</div>
