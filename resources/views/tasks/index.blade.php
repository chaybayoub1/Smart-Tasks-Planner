{{-- resources/views/tasks/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Study Planner')
@section('page-title', 'Study Planner')

@section('content')
<div class="row g-4">

    {{-- ── ADD TASK FORM ──────────────────────────────────────── --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <i class="bi bi-plus-circle-fill text-primary me-2"></i>
                <span>Add New Task</span>
                <button class="btn btn-sm btn-light ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#addTaskForm">
                    <i class="bi bi-chevron-down"></i>
                </button>
            </div>
            <div class="collapse show" id="addTaskForm">
                <div class="card-body">
                    <form method="POST" action="{{ route('tasks.store') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-500 small">Title *</label>
                                <input type="text" name="title" class="form-control" placeholder="e.g. Chapter 5 revision" value="{{ old('title') }}" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-500 small">Subject</label>
                                <select name="subject_id" class="form-select">
                                    <option value="">— None —</option>
                                    @foreach($subjects as $s)
                                        <option value="{{ $s->id }}" {{ old('subject_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-500 small">Due Date *</label>
                                <input type="date" name="due_date" class="form-control" value="{{ old('due_date', today()->toDateString()) }}" required>
                            </div>
                            <div class="col-md-1">
                                <label class="form-label fw-500 small">Mins *</label>
                                <input type="number" name="duration" class="form-control" value="{{ old('duration', 60) }}" min="1" max="1440" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-500 small">Priority</label>
                                <select name="priority" class="form-select">
                                    <option value="low">🟢 Low</option>
                                    <option value="medium" selected>🟡 Medium</option>
                                    <option value="high">🔴 High</option>
                                </select>
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button class="btn btn-primary w-100">Add</button>
                            </div>
                            <div class="col-12">
                                <input type="text" name="description" class="form-control form-control-sm" placeholder="Description (optional)" value="{{ old('description') }}">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ── MINI CALENDAR ──────────────────────────────────────── --}}
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <a href="?month={{ $month == 1 ? 12 : $month-1 }}&year={{ $month == 1 ? $year-1 : $year }}" class="btn btn-sm btn-light">‹</a>
                <span class="fw-600">{{ \Carbon\Carbon::create($year,$month,1)->format('F Y') }}</span>
                <a href="?month={{ $month == 12 ? 1 : $month+1 }}&year={{ $month == 12 ? $year+1 : $year }}" class="btn btn-sm btn-light">›</a>
            </div>
            <div class="card-body p-2">
                <div class="calendar-grid">
                    @php
                        $daysInMonth = \Carbon\Carbon::create($year,$month,1)->daysInMonth;
                        $startDay    = \Carbon\Carbon::create($year,$month,1)->dayOfWeek; // 0=Sun
                        $today       = now()->day;
                        $isThisMonth = now()->month == $month && now()->year == $year;
                    @endphp
                    <div class="row g-0 text-center mb-1">
                        @foreach(['Su','Mo','Tu','We','Th','Fr','Sa'] as $d)
                            <div class="col" style="font-size:.7rem;color:#999;font-weight:600">{{ $d }}</div>
                        @endforeach
                    </div>
                    <div class="row g-0 text-center">
                        @for($i = 0; $i < $startDay; $i++)
                            <div class="col" style="min-height:36px"></div>
                        @endfor
                        @for($day = 1; $day <= $daysInMonth; $day++)
                            @php
                                $hasTask    = isset($calTasks[$day]);
                                $isToday    = $isThisMonth && $day === $today;
                            @endphp
                            <div class="col d-flex align-items-center justify-content-center" style="min-height:36px; position:relative">
                                <span class="d-flex align-items-center justify-content-center rounded-circle"
                                      style="width:30px;height:30px;font-size:.8rem;cursor:default;
                                             {{ $isToday ? 'background:#6366f1;color:#fff;font-weight:700' : ($hasTask ? 'background:#e0e7ff;color:#4f46e5;font-weight:600' : '') }}">
                                    {{ $day }}
                                </span>
                                @if($hasTask)
                                    <span style="position:absolute;bottom:2px;left:50%;transform:translateX(-50%);
                                                 width:4px;height:4px;border-radius:50%;background:#6366f1"></span>
                                @endif
                            </div>
                            @if(($day + $startDay) % 7 === 0)
                                </div><div class="row g-0 text-center">
                            @endif
                        @endfor
                    </div>
                </div>
                <hr class="my-2">
                <div style="max-height:200px;overflow-y:auto">
                    @foreach($calTasks as $day => $dayTasks)
                        <div class="mb-2">
                            <div class="fw-600 small text-muted">{{ \Carbon\Carbon::create($year,$month,$day)->format('M d') }}</div>
                            @foreach($dayTasks as $t)
                                <div class="d-flex align-items-center gap-1 ps-2">
                                    <span style="width:6px;height:6px;border-radius:50%;background:{{ $t->subject?->color ?? '#6366f1' }};flex-shrink:0"></span>
                                    <span class="small text-truncate">{{ $t->title }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                    @if($calTasks->isEmpty())
                        <p class="text-muted small text-center">No tasks this month</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ── TASK LIST ──────────────────────────────────────────── --}}
    <div class="col-md-8">
        {{-- Filters --}}
        <form method="GET" class="card mb-3">
            <div class="card-body py-2">
                <div class="row g-2 align-items-center">
                    <div class="col-auto">
                        <select name="subject_id" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">All Subjects</option>
                            @foreach($subjects as $s)
                                <option value="{{ $s->id }}" {{ request('subject_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto">
                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">All Status</option>
                            <option value="pending"     {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed"   {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <select name="priority" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">All Priority</option>
                            <option value="high"   {{ request('priority') == 'high' ? 'selected' : '' }}>🔴 High</option>
                            <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>🟡 Medium</option>
                            <option value="low"    {{ request('priority') == 'low' ? 'selected' : '' }}>🟢 Low</option>
                        </select>
                    </div>
                    @if(request()->hasAny(['subject_id','status','priority']))
                        <div class="col-auto">
                            <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
                        </div>
                    @endif
                </div>
            </div>
        </form>

        {{-- Task cards --}}
        <div class="d-flex flex-column gap-2">
            @forelse($tasks as $task)
            <div class="card {{ $task->isOverdue() ? 'border-danger border-opacity-50' : '' }}">
                <div class="card-body py-2 px-3">
                    <div class="d-flex align-items-center gap-3">
                        {{-- Toggle button --}}
                        <form method="POST" action="{{ route('tasks.toggle', $task) }}">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm p-0 border-0 text-{{ $task->status === 'completed' ? 'success' : 'secondary' }}">
                                <i class="bi bi-{{ $task->status === 'completed' ? 'check-circle-fill' : 'circle' }} fs-4"></i>
                            </button>
                        </form>

                        {{-- Info --}}
                        <div class="flex-grow-1 min-w-0">
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="fw-500 {{ $task->status === 'completed' ? 'text-decoration-line-through text-muted' : '' }}">
                                    {{ $task->title }}
                                </span>
                                <span class="badge text-bg-{{ $task->priorityBadgeClass() }}">{{ $task->priority }}</span>
                                <span class="badge text-bg-{{ $task->statusBadgeClass() }}">{{ str_replace('_',' ',$task->status) }}</span>
                                @if($task->isOverdue())
                                    <span class="badge text-bg-danger">Overdue</span>
                                @endif
                            </div>
                            <div class="small text-muted d-flex gap-2 flex-wrap mt-1">
                                @if($task->subject)
                                    <span><span class="rounded-circle d-inline-block me-1" style="width:8px;height:8px;background:{{ $task->subject->color }}"></span>{{ $task->subject->name }}</span>
                                @endif
                                <span><i class="bi bi-calendar3"></i> {{ $task->due_date->format('M d, Y') }}</span>
                                <span><i class="bi bi-clock"></i> {{ $task->duration }}min</span>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="d-flex gap-1">
                            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Delete task?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-light text-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="card">
                <div class="card-body text-center py-5 text-muted">
                    <i class="bi bi-calendar-x fs-1 d-block mb-3"></i>
                    No tasks found. Add your first task above!
                </div>
            </div>
            @endforelse
        </div>
        <div class="mt-3">{{ $tasks->links() }}</div>
    </div>
</div>
@endsection
