{{-- resources/views/tasks/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Study Planner')
@section('page-title', 'Study Planner')

@section('content')
<div class="row g-4">

    {{-- ── ADD TASK FORM ──────────────────────────────────────── --}}
    <div class="col-12">
        <div class="st-card">
            <div class="st-card-header">
                <div class="st-card-title">
                    <div class="icon icon-teal"><i class="bi bi-plus-circle-fill"></i></div>
                    Add New Task
                </div>
                <button type="button" class="btn-st-ghost" data-bs-toggle="collapse" data-bs-target="#addTaskForm" style="font-size:.75rem;padding:.3rem .65rem">
                    <i class="bi bi-chevron-down"></i>
                </button>
            </div>
            <div class="collapse show" id="addTaskForm">
                <div style="padding:1.25rem">
                    <form method="POST" action="{{ route('tasks.store') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="st-label">Title *</label>
                                <input type="text" name="title" class="st-input" placeholder="e.g. Chapter 5 revision" value="{{ old('title') }}" required>
                            </div>
                            <div class="col-md-2">
                                <label class="st-label">Subject</label>
                                <select name="subject_id" class="st-select">
                                    <option value="">— None —</option>
                                    @foreach($subjects as $s)
                                        <option value="{{ $s->id }}" {{ old('subject_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="st-label">Due Date *</label>
                                <input type="date" name="due_date" class="st-input" value="{{ old('due_date', today()->toDateString()) }}" required>
                            </div>
                            <div class="col-md-1">
                                <label class="st-label">Mins *</label>
                                <input type="number" name="duration" class="st-input" value="{{ old('duration', 60) }}" min="1" max="1440" required>
                            </div>
                            <div class="col-md-2">
                                <label class="st-label">Priority</label>
                                <select name="priority" class="st-select">
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button class="btn-st-primary w-100" style="justify-content:center">Add</button>
                            </div>
                            <div class="col-12">
                                <input type="text" name="description" class="st-input" placeholder="Description (optional)" value="{{ old('description') }}">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ── MINI CALENDAR ──────────────────────────────────────── --}}
    <div class="col-md-4">
        <div class="st-card h-100">
            <div class="st-card-header">
                <a href="?month={{ $month == 1 ? 12 : $month-1 }}&year={{ $month == 1 ? $year-1 : $year }}" class="btn-st-ghost" style="padding:.3rem .6rem;font-size:.85rem">&lsaquo;</a>
                <div class="st-card-title" style="justify-content:center">
                    {{ \Carbon\Carbon::create($year,$month,1)->format('F Y') }}
                </div>
                <a href="?month={{ $month == 12 ? 1 : $month+1 }}&year={{ $month == 12 ? $year+1 : $year }}" class="btn-st-ghost" style="padding:.3rem .6rem;font-size:.85rem">&rsaquo;</a>
            </div>
            <div style="padding:.75rem">
                @php
                    $daysInMonth = \Carbon\Carbon::create($year,$month,1)->daysInMonth;
                    $startDay    = \Carbon\Carbon::create($year,$month,1)->dayOfWeek;
                    $today       = now()->day;
                    $isThisMonth = now()->month == $month && now()->year == $year;
                @endphp

                {{-- Day headers --}}
                <div style="display:grid;grid-template-columns:repeat(7,1fr);text-align:center;margin-bottom:.5rem">
                    @foreach(['Su','Mo','Tu','We','Th','Fr','Sa'] as $d)
                        <div style="font-size:.67rem;font-weight:700;color:var(--c-muted);padding:.25rem 0">{{ $d }}</div>
                    @endforeach
                </div>

                {{-- Day cells --}}
                <div style="display:grid;grid-template-columns:repeat(7,1fr);text-align:center">
                    @for($i = 0; $i < $startDay; $i++)<div></div>@endfor
                    @for($day = 1; $day <= $daysInMonth; $day++)
                        @php $hasTask = isset($calTasks[$day]); $isToday = $isThisMonth && $day === $today; @endphp
                        <div style="position:relative;padding:2px 0">
                            <div class="cal-day {{ $isToday ? 'today' : ($hasTask ? 'has-task' : '') }}">{{ $day }}</div>
                            @if($hasTask && !$isToday)
                                <div style="width:4px;height:4px;border-radius:50%;background:var(--c-teal);margin:1px auto 0"></div>
                            @endif
                        </div>
                        @if(($day + $startDay) % 7 === 0 && $day < $daysInMonth)
                            {{-- row break handled by grid auto-wrap --}}
                        @endif
                    @endfor
                </div>

                <div style="margin-top:1rem;border-top:1px solid var(--c-border);padding-top:.75rem;max-height:180px;overflow-y:auto">
                    @foreach($calTasks as $day => $dayTasks)
                        <div style="margin-bottom:.6rem">
                            <div style="font-size:.72rem;font-weight:700;color:var(--c-muted2);margin-bottom:.2rem">
                                {{ \Carbon\Carbon::create($year,$month,$day)->format('M d') }}
                            </div>
                            @foreach($dayTasks as $t)
                                <div style="display:flex;align-items:center;gap:.4rem;padding:.1rem 0">
                                    <span style="width:6px;height:6px;border-radius:50%;background:{{ $t->subject?->color ?? 'var(--c-teal)' }};flex-shrink:0"></span>
                                    <span style="font-size:.78rem;color:var(--c-text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $t->title }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                    @if($calTasks->isEmpty())
                        <p style="font-size:.78rem;color:var(--c-muted);text-align:center;margin:0">No tasks this month</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ── TASK LIST ──────────────────────────────────────────── --}}
    <div class="col-md-8">

        {{-- Filters --}}
        <form method="GET" style="margin-bottom:1rem">
            <div style="display:flex;gap:.5rem;align-items:center;flex-wrap:wrap">
                <select name="subject_id" class="st-select" style="width:auto;min-width:130px" onchange="this.form.submit()">
                    <option value="">All Subjects</option>
                    @foreach($subjects as $s)
                        <option value="{{ $s->id }}" {{ request('subject_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                    @endforeach
                </select>
                <select name="status" class="st-select" style="width:auto;min-width:130px" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="pending"     {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed"   {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
                <select name="priority" class="st-select" style="width:auto;min-width:120px" onchange="this.form.submit()">
                    <option value="">All Priority</option>
                    <option value="high"   {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="low"    {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                </select>
                @if(request()->hasAny(['subject_id','status','priority']))
                    <a href="{{ route('tasks.index') }}" class="btn-st-ghost" style="font-size:.78rem;padding:.45rem .75rem">Clear</a>
                @endif
            </div>
        </form>

        {{-- Task cards --}}
        <div style="display:flex;flex-direction:column;gap:.6rem">
            @forelse($tasks as $task)
            <div class="st-card task-row {{ $task->isOverdue() ? 'overdue' : '' }}" style="{{ $task->isOverdue() ? 'border-color:rgba(255,107,107,.3)' : '' }}">
                <div style="display:flex;align-items:center;gap:.85rem;padding:.85rem 1.1rem">

                    {{-- Toggle --}}
                    <form method="POST" action="{{ route('tasks.toggle', $task) }}" style="flex-shrink:0">
                        @csrf @method('PATCH')
                        <button style="background:none;border:none;cursor:pointer;padding:0;font-size:1.25rem;color:{{ $task->status === 'completed' ? 'var(--c-teal)' : 'var(--c-muted)' }};transition:color .15s;line-height:1">
                            <i class="bi bi-{{ $task->status === 'completed' ? 'check-circle-fill' : 'circle' }}"></i>
                        </button>
                    </form>

                    {{-- Info --}}
                    <div style="flex:1;min-width:0">
                        <div style="display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;margin-bottom:.3rem">
                            <span style="font-weight:600;font-size:.9rem;color:var(--c-text);{{ $task->status === 'completed' ? 'text-decoration:line-through;opacity:.45' : '' }}">
                                {{ $task->title }}
                            </span>
                            @php $pc = ['high'=>'coral','medium'=>'amber','low'=>'teal'][$task->priority] ?? 'muted'; @endphp
                            <span class="tag tag-{{ $pc }}">{{ $task->priority }}</span>
                            @if($task->isOverdue())
                                <span class="tag tag-coral">Overdue</span>
                            @endif
                        </div>
                        <div style="display:flex;align-items:center;gap:.6rem;flex-wrap:wrap">
                            @if($task->subject)
                                <span class="tag" style="background:{{ $task->subject->color }}15;color:{{ $task->subject->color }};border-color:{{ $task->subject->color }}30;font-size:.67rem">
                                    {{ $task->subject->name }}
                                </span>
                            @endif
                            <span style="font-size:.73rem;color:var(--c-muted)">
                                <i class="bi bi-calendar3" style="margin-right:.2rem"></i>{{ $task->due_date->format('M d, Y') }}
                            </span>
                            <span style="font-size:.73rem;color:var(--c-muted)">
                                <i class="bi bi-clock" style="margin-right:.2rem"></i>{{ $task->duration }}min
                            </span>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div style="display:flex;gap:.4rem;flex-shrink:0">
                        <a href="{{ route('tasks.edit', $task) }}" class="btn-st-ghost" style="padding:.35rem .6rem;font-size:.85rem">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Delete this task?')">
                            @csrf @method('DELETE')
                            <button class="btn-st-danger" style="padding:.35rem .6rem;font-size:.85rem">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="st-card">
                <div class="empty-state">
                    <div class="empty-state-icon"><i class="bi bi-calendar-x"></i></div>
                    <p>No tasks found.<br><a href="#addTaskForm" onclick="document.getElementById('addTaskForm').classList.add('show')">Add your first task above!</a></p>
                </div>
            </div>
            @endforelse
        </div>

        <div style="margin-top:1.25rem">{{ $tasks->links() }}</div>
    </div>
</div>
@endsection
