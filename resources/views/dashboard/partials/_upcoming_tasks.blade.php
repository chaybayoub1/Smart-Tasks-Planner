{{--
    resources/views/dashboard/partials/_upcoming_tasks.blade.php

    Purpose  : Scrollable list of the user's next upcoming tasks with
               inline toggle-complete forms, subject badge, due date,
               completed Pomodoro study time, and priority badge.
    Included : dashboard/partials/_activity_row.blade.php
    Data     : $upcomingTasks (Collection<Task>)
               Each task exposes: title, status, due_date,
               priority, subject (relation), priorityBadgeClass().
--}}
<div class="task-list-card h-100">
    <div class="task-list-card-header">
        <span><i class="bi bi-calendar-week me-2"></i>Upcoming Tasks</span>
        <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-outline-secondary py-0" style="font-size:.75rem;">All Tasks</a>
    </div>

    @forelse($upcomingTasks as $task)
    <div class="d-flex align-items-center gap-3 px-4 py-3 border-bottom">
        <form method="POST" action="{{ route('tasks.toggle', $task) }}">
            @csrf @method('PATCH')
            <button class="btn btn-sm p-0 border-0 text-{{ $task->status === 'completed' ? 'success' : 'secondary' }}">
                <i class="bi bi-{{ $task->status === 'completed' ? 'check-circle-fill' : 'circle' }} fs-5"></i>
            </button>
        </form>
        <div class="flex-grow-1 min-w-0">
            <div class="fw-600 text-truncate {{ $task->status === 'completed' ? 'text-decoration-line-through text-muted' : '' }}" style="font-size:.88rem;">
                {{ $task->title }}
            </div>
            <div style="font-size:.72rem; color:var(--db-muted);">
                @if($task->subject)
                    <span class="badge me-1" style="background:{{ $task->subject->color }}20;color:{{ $task->subject->color }};font-size:.65rem;">{{ $task->subject->name }}</span>
                @endif
                {{ $task->due_date->format('M d') }} · {{ $task->completedPomodoroCount() }} {{ \Illuminate\Support\Str::plural('session', $task->completedPomodoroCount()) }} · {{ $task->studiedMinutes() }}min studied
            </div>
        </div>
        <span class="badge text-bg-{{ $task->priorityBadgeClass() }}" style="font-size:.65rem;">{{ $task->priority }}</span>
    </div>
    @empty
    <div class="text-center py-5" style="color:var(--db-muted);">
        <i class="bi bi-calendar-check fs-2 d-block mb-2 text-success"></i>
        <span style="font-size:.85rem;">No upcoming tasks. <a href="{{ route('tasks.index') }}">Add one!</a></span>
    </div>
    @endforelse
</div>
