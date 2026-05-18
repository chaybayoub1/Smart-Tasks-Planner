{{--
    resources/views/dashboard/partials/_quick_actions.blade.php

    Purpose  : Full-width quick-action button bar at the bottom of the
               dashboard (Start Pomodoro / New Note / Review Cards /
               Plan Task / Add Exam).
    Included : dashboard/index.blade.php, at the end of @section('content').
    Data     : None — all links use named routes.
--}}
<div class="quick-actions-card">
    <p class="db-section-label" style="margin-bottom:14px;">Quick Actions</p>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('pomodoro.index') }}" class="qa-btn qa-btn-primary">
            <i class="bi bi-stopwatch-fill"></i> Start Pomodoro
        </a>
        <a href="{{ route('notes.index') }}" class="qa-btn qa-btn-outline">
            <i class="bi bi-plus-lg"></i> New Note
        </a>
        <a href="{{ route('flashcards.review') }}" class="qa-btn qa-btn-outline">
            <i class="bi bi-layers-fill"></i> Review Cards
        </a>
        <a href="{{ route('tasks.index') }}" class="qa-btn qa-btn-outline">
            <i class="bi bi-calendar-plus"></i> Plan Task
        </a>
        <a href="{{ route('exams.index') }}" class="qa-btn qa-btn-outline">
            <i class="bi bi-journal-check"></i> Add Exam
        </a>
    </div>
</div>
