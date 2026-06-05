{{-- resources/views/groups/show.blade.php --}}
@extends('layouts.app')
@section('title', $group->name)
@section('page-title', 'Collaboration / ' . $group->name)

@push('styles')
    @include('dashboard.partials._dashboard_styles')
@endpush

@section('content')
<div class="d-flex align-items-start justify-content-between gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1">{{ $group->name }}</h4>
        <p class="text-muted mb-0">{{ $group->description ?: 'No description yet.' }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('groups.index') }}" class="btn btn-light">
            <i class="bi bi-arrow-left"></i> Groups
        </a>
        @if($group->isOwner(auth()->user()))
            <form method="POST" action="{{ route('groups.destroy', $group) }}" onsubmit="return confirm('Delete this group?')">
                @csrf @method('DELETE')
                <button class="btn btn-outline-danger">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
        @endif
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-8">
        @if($canManage)
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center">
                    <i class="bi bi-person-check-fill text-primary me-2"></i>
                    <span>Assign New Task</span>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('groups.tasks.store', $group) }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-500 small">Title *</label>
                                <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-500 small">Assign to</label>
                                <select name="assigned_to" class="form-select">
                                    <option value="">Unassigned</option>
                                    @foreach($group->members as $member)
                                        <option value="{{ $member->id }}" {{ old('assigned_to') == $member->id ? 'selected' : '' }}>
                                            {{ $member->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-500 small">Due *</label>
                                <input type="date" name="due_date" class="form-control" value="{{ old('due_date', today()->toDateString()) }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-500 small">Priority</label>
                                <select name="priority" class="form-select">
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-500 small">Subject</label>
                                <select name="subject_id" class="form-select">
                                    <option value="">None</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label fw-500 small">Description</label>
                                <input type="text" name="description" class="form-control" value="{{ old('description') }}">
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary">
                                    <i class="bi bi-send-check me-1"></i> Assign Task
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <div class="card">
            <div class="card-header d-flex align-items-center">
                <i class="bi bi-list-task text-primary me-2"></i>
                <span>Group Tasks</span>
            </div>
            <div class="card-body">
                <div class="d-flex flex-column gap-3">
                    @forelse($group->tasks as $task)
                        <div class="card planner-task-card {{ $task->isOverdue() ? 'is-overdue' : '' }} {{ $task->status === 'completed' ? 'is-completed' : '' }}">
                            <div class="card-body planner-task-card-body">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="flex-grow-1 min-w-0">
                                        <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                            <span class="planner-task-title {{ $task->status === 'completed' ? 'text-decoration-line-through text-muted' : '' }}">
                                                {{ $task->title }}
                                            </span>
                                            <span class="planner-task-badge priority-{{ $task->priority }}">{{ $task->priority }}</span>
                                            <span class="planner-task-badge status-{{ $task->status }}">{{ str_replace('_',' ',$task->status) }}</span>
                                            @if($task->isOverdue())
                                                <span class="planner-task-badge is-overdue">Overdue</span>
                                            @endif
                                        </div>
                                        <div class="planner-task-meta d-flex gap-3 flex-wrap">
                                            @if($task->subject)
                                                <span><span class="planner-task-dot" style="background:{{ $task->subject->color }}"></span>{{ $task->subject->name }}</span>
                                            @endif
                                            <span><i class="bi bi-person"></i> {{ $task->assignee?->name ?? 'Unassigned' }}</span>
                                            <span><i class="bi bi-calendar3"></i> {{ $task->due_date->format('M d, Y') }}</span>
                                            <span><i class="bi bi-stopwatch"></i> {{ $task->completedPomodoroCount() }} sessions &bull; {{ $task->studiedMinutes() }}min studied</span>
                                        </div>
                                        @if($task->description)
                                            <div class="small text-muted mt-2">{{ $task->description }}</div>
                                        @endif
                                    </div>

                                    <div class="planner-task-actions">
                                        @can('update', $task)
                                            <form method="POST" action="{{ route('groups.tasks.status', [$group, $task]) }}">
                                                @csrf @method('PATCH')
                                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()" style="width:130px">
                                                    @foreach(['pending' => 'Pending', 'in_progress' => 'In progress', 'completed' => 'Completed'] as $value => $label)
                                                        <option value="{{ $value }}" {{ $task->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                            </form>
                                        @endcan
                                        @can('delete', $task)
                                            <form method="POST" action="{{ route('groups.tasks.destroy', [$group, $task]) }}" onsubmit="return confirm('Delete this task?')">
                                                @csrf @method('DELETE')
                                                <button class="planner-task-action is-danger" aria-label="Delete task"><i class="bi bi-trash"></i></button>
                                            </form>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-check2-square fs-1 d-block mb-3"></i>
                            No group tasks yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center">
                <i class="bi bi-person-plus-fill text-primary me-2"></i>
                <span>Members</span>
            </div>
            <div class="card-body">
                @if($canManage)
                    <form method="POST" action="{{ route('groups.invitations.store', $group) }}" class="d-flex gap-2 mb-3">
                        @csrf
                        <input type="email" name="email" class="form-control" placeholder="member@email.com" required>
                        <button class="btn btn-primary">Invite</button>
                    </form>
                @endif

                <div class="d-flex flex-column gap-2">
                    @foreach($group->members as $member)
                        <div class="d-flex align-items-center justify-content-between border rounded-3 p-2">
                            <div>
                                <div class="fw-semibold small">{{ $member->name }}</div>
                                <div class="text-muted" style="font-size:.72rem">{{ $member->email }}</div>
                            </div>
                            <span class="badge text-bg-light">{{ $member->pivot->role }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex align-items-center">
                <i class="bi bi-envelope-fill text-primary me-2"></i>
                <span>Invitations</span>
            </div>
            <div class="card-body">
                @forelse($group->invitations as $invitation)
                    <div class="border rounded-3 p-2 mb-2">
                        <div class="d-flex align-items-center justify-content-between gap-2">
                            <div class="small fw-semibold text-truncate">{{ $invitation->email }}</div>
                            <span class="badge text-bg-{{ $invitation->status === 'accepted' ? 'success' : ($invitation->status === 'declined' ? 'secondary' : 'warning') }}">
                                {{ $invitation->status }}
                            </span>
                        </div>
                        @if($invitation->status === 'pending')
                            <div class="small text-muted mt-1">
                                Link: {{ route('groups.invitations.respond', $invitation->token) }}
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-muted small mb-0">No invitations yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
