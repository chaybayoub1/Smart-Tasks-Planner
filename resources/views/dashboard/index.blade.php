{{-- resources/views/dashboard/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- ── WELCOME ROW ─────────────────────────────────────────── --}}
<div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem">
    <div>
        <div class="section-title" style="margin-bottom:.25rem">
            Welcome back, <span>{{ auth()->user()->name }}</span>
        </div>
        <p style="color:var(--c-muted);font-size:.85rem;margin:0">
            Here's what's on your plate today.
        </p>
    </div>
    <div style="display:flex;gap:.5rem;flex-wrap:wrap">
        <a href="{{ route('pomodoro.index') }}" class="btn-st-primary">
            <i class="bi bi-stopwatch-fill"></i> Start Focus
        </a>
        <a href="{{ route('tasks.index') }}" class="btn-st-ghost">
            <i class="bi bi-plus-lg"></i> Add Task
        </a>
    </div>
</div>

{{-- ── STAT CARDS ──────────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card teal">
            <div class="stat-value">{{ floor($totalMinutes / 60) }}h {{ $totalMinutes % 60 }}m</div>
            <div class="stat-label">Total Study Time</div>
            <i class="bi bi-clock-fill stat-bg-icon"></i>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card amber">
            <div class="stat-value">{{ $streak->current_streak ?? 0 }}</div>
            <div class="stat-label">Day Streak</div>
            <i class="bi bi-fire stat-bg-icon"></i>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card coral">
            <div class="stat-value">{{ $totalSessions }}</div>
            <div class="stat-label">Pomodoro Sessions</div>
            <i class="bi bi-stopwatch-fill stat-bg-icon"></i>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card violet">
            <div class="stat-value">{{ $user->level }}</div>
            <div class="stat-label">Current Level</div>
            <i class="bi bi-star-fill stat-bg-icon"></i>
        </div>
    </div>
</div>

{{-- ── ROW 2: Chart + Notifications ──────────────────────── --}}
<div class="row g-3 mb-4">

    {{-- Weekly Chart --}}
    <div class="col-md-7">
        <div class="st-card h-100">
            <div class="st-card-header">
                <div class="st-card-title">
                    <div class="icon icon-teal"><i class="bi bi-bar-chart-fill"></i></div>
                    Weekly Focus Minutes
                </div>
                <span class="tag tag-muted">Last 7 days</span>
            </div>
            <div style="padding:1.25rem">
                <canvas id="weeklyChart" height="90"></canvas>
            </div>
        </div>
    </div>

    {{-- Notifications --}}
    <div class="col-md-5">
        <div class="st-card h-100">
            <div class="st-card-header">
                <div class="st-card-title">
                    <div class="icon icon-amber"><i class="bi bi-bell-fill"></i></div>
                    Notifications
                </div>
            </div>
            <div>
                @if($overdueTasks > 0)
                <div class="st-list-item">
                    <div style="width:30px;height:30px;border-radius:8px;background:rgba(255,107,107,.12);display:flex;align-items:center;justify-content:center;color:var(--c-coral);flex-shrink:0">
                        <i class="bi bi-exclamation-triangle-fill" style="font-size:.85rem"></i>
                    </div>
                    <div style="flex:1;min-width:0">
                        <div style="font-size:.82rem;font-weight:600;color:var(--c-coral)">{{ $overdueTasks }} overdue task{{ $overdueTasks > 1 ? 's' : '' }}</div>
                        <div style="font-size:.73rem;color:var(--c-muted)">Needs attention</div>
                    </div>
                    <a href="{{ route('tasks.index', ['status'=>'pending']) }}" class="btn-st-danger">View</a>
                </div>
                @endif

                @if($flashcardsDue > 0)
                <div class="st-list-item">
                    <div style="width:30px;height:30px;border-radius:8px;background:rgba(0,212,170,.12);display:flex;align-items:center;justify-content:center;color:var(--c-teal);flex-shrink:0">
                        <i class="bi bi-layers-fill" style="font-size:.85rem"></i>
                    </div>
                    <div style="flex:1;min-width:0">
                        <div style="font-size:.82rem;font-weight:600;color:var(--c-teal)">{{ $flashcardsDue }} flashcard{{ $flashcardsDue > 1 ? 's' : '' }} due</div>
                        <div style="font-size:.73rem;color:var(--c-muted)">Ready to review</div>
                    </div>
                    <a href="{{ route('flashcards.review') }}" class="btn-st-ghost" style="font-size:.75rem;padding:.3rem .65rem">Review</a>
                </div>
                @endif

                @foreach($upcomingExams->take(3) as $exam)
                <div class="st-list-item">
                    <div style="width:30px;height:30px;border-radius:8px;background:rgba(124,106,247,.12);display:flex;align-items:center;justify-content:center;color:var(--c-violet);flex-shrink:0">
                        <i class="bi bi-calendar-event" style="font-size:.85rem"></i>
                    </div>
                    <div style="flex:1;min-width:0">
                        <div style="font-size:.82rem;font-weight:600;color:var(--c-text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $exam->title }}</div>
                        <div style="font-size:.73rem;color:var(--c-muted)">in {{ $exam->daysUntil() }} day{{ $exam->daysUntil() != 1 ? 's' : '' }}</div>
                    </div>
                </div>
                @endforeach

                @if($overdueTasks === 0 && $flashcardsDue === 0 && $upcomingExams->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon" style="background:rgba(0,212,170,.08);color:var(--c-teal)">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <p style="color:var(--c-muted2)">All caught up! Keep it up.</p>
                </div>
                @endif

                <div style="padding:.65rem 1.25rem;border-top:1px solid var(--c-border);display:flex;gap:1rem">
                    <span style="font-size:.75rem;color:var(--c-muted)">
                        <i class="bi bi-journal-text me-1" style="color:var(--c-teal)"></i>{{ $totalNotes }} Notes
                    </span>
                    <span style="font-size:.75rem;color:var(--c-muted)">
                        <i class="bi bi-layers me-1" style="color:var(--c-violet)"></i>{{ $totalFlashcards }} Cards
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── ROW 3: Tasks + XP / Badges ──────────────────────────── --}}
<div class="row g-3 mb-4">

    {{-- Upcoming Tasks --}}
    <div class="col-md-6">
        <div class="st-card h-100">
            <div class="st-card-header">
                <div class="st-card-title">
                    <div class="icon icon-teal"><i class="bi bi-check2-square"></i></div>
                    Upcoming Tasks
                </div>
                <a href="{{ route('tasks.index') }}" class="btn-st-ghost" style="font-size:.75rem;padding:.3rem .65rem">All Tasks</a>
            </div>
            <div>
                @forelse($upcomingTasks as $task)
                <div class="st-list-item">
                    <form method="POST" action="{{ route('tasks.toggle', $task) }}" style="flex-shrink:0">
                        @csrf @method('PATCH')
                        <button style="background:none;border:none;cursor:pointer;padding:0;color:{{ $task->status === 'completed' ? 'var(--c-teal)' : 'var(--c-muted)' }};font-size:1.1rem;transition:color .15s">
                            <i class="bi bi-{{ $task->status === 'completed' ? 'check-circle-fill' : 'circle' }}"></i>
                        </button>
                    </form>
                    <div style="flex:1;min-width:0">
                        <div style="font-size:.85rem;font-weight:500;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:var(--c-text);{{ $task->status === 'completed' ? 'text-decoration:line-through;opacity:.5' : '' }}">
                            {{ $task->title }}
                        </div>
                        <div style="display:flex;align-items:center;gap:.4rem;margin-top:.15rem">
                            @if($task->subject)
                                <span class="tag" style="background:{{ $task->subject->color }}18;color:{{ $task->subject->color }};border-color:{{ $task->subject->color }}30;font-size:.67rem">
                                    {{ $task->subject->name }}
                                </span>
                            @endif
                            <span style="font-size:.72rem;color:var(--c-muted)">
                                {{ $task->due_date->format('M d') }} &middot; {{ $task->duration }}min
                            </span>
                        </div>
                    </div>
                    @php $pc = ['high'=>'coral','medium'=>'amber','low'=>'teal'][$task->priority] ?? 'muted'; @endphp
                    <span class="tag tag-{{ $pc }}">{{ $task->priority }}</span>
                </div>
                @empty
                <div class="empty-state">
                    <div class="empty-state-icon"><i class="bi bi-calendar-check"></i></div>
                    <p>No upcoming tasks.<br><a href="{{ route('tasks.index') }}">Add one!</a></p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- XP + Badges --}}
    <div class="col-md-6">

        {{-- Level card --}}
        <div class="st-card mb-3">
            <div style="padding:1.25rem">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:.85rem">
                    <div>
                        <div style="font-family:var(--font-display);font-size:1.1rem;font-weight:800;color:var(--c-text)">Level {{ $user->level }}</div>
                        <div style="font-size:.75rem;color:var(--c-muted);margin-top:.15rem">
                            {{ number_format($user->xp) }} / {{ number_format($user->xpForNextLevel()) }} XP
                        </div>
                    </div>
                    <div style="font-family:var(--font-display);font-size:1.5rem;font-weight:800;color:var(--c-amber)">
                        {{ $user->xpProgress() }}<span style="font-size:.9rem">%</span>
                    </div>
                </div>
                <div class="st-progress">
                    <div class="st-progress-fill teal" style="width:{{ $user->xpProgress() }}%"></div>
                </div>
                <div style="display:flex;justify-content:space-between;margin-top:.5rem">
                    <span style="font-size:.72rem;color:var(--c-muted)">Level {{ $user->level }}</span>
                    <span style="font-size:.72rem;color:var(--c-muted)">Level {{ $user->level + 1 }}</span>
                </div>
            </div>
        </div>

        {{-- Badges --}}
        <div class="st-card">
            <div class="st-card-header">
                <div class="st-card-title">
                    <div class="icon icon-amber"><i class="bi bi-award-fill"></i></div>
                    Badges
                </div>
                <span style="font-size:.75rem;color:var(--c-muted)">{{ $badges->count() }} earned</span>
            </div>
            <div style="padding:1.1rem">
                @if($badges->isEmpty())
                    <p style="font-size:.82rem;color:var(--c-muted);margin:0">Complete sessions to earn badges!</p>
                @else
                <div style="display:flex;flex-wrap:wrap;gap:.6rem">
                    @foreach($badges as $badge)
                    <div style="text-align:center;width:56px" title="{{ $badge->name }}: {{ $badge->description }}"
                         data-bs-toggle="tooltip">
                        <div style="width:44px;height:44px;border-radius:12px;background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.2);display:flex;align-items:center;justify-content:center;font-size:1.3rem;margin:0 auto .3rem">{{ $badge->icon }}</div>
                        <div style="font-size:.62rem;color:var(--c-muted);overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $badge->name }}</div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ── QUICK ACTIONS ────────────────────────────────────────── --}}
<div class="st-card">
    <div style="padding:1.1rem 1.25rem">
        <div style="font-family:var(--font-display);font-size:.82rem;font-weight:700;color:var(--c-muted2);text-transform:uppercase;letter-spacing:.08em;margin-bottom:.9rem">Quick Actions</div>
        <div style="display:flex;flex-wrap:wrap;gap:.6rem">
            <a href="{{ route('pomodoro.index') }}" class="btn-st-primary">
                <i class="bi bi-stopwatch-fill"></i> Start Pomodoro
            </a>
            <a href="{{ route('notes.index') }}" class="btn-st-ghost">
                <i class="bi bi-journal-plus"></i> New Note
            </a>
            <a href="{{ route('flashcards.review') }}" class="btn-st-ghost">
                <i class="bi bi-layers"></i> Review Cards
            </a>
            <a href="{{ route('tasks.index') }}" class="btn-st-ghost">
                <i class="bi bi-calendar-plus"></i> Plan Task
            </a>
            <a href="{{ route('exams.index') }}" class="btn-st-ghost">
                <i class="bi bi-journal-check"></i> Add Exam
            </a>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Weekly study chart
const ctx = document.getElementById('weeklyChart');
const weeklyData = @json($weeklyData);

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: weeklyData.map(d => new Date(d.date).toLocaleDateString('en', {weekday:'short'})),
        datasets: [{
            label: 'Minutes',
            data: weeklyData.map(d => d.minutes),
            backgroundColor: weeklyData.map((d,i) =>
                i === weeklyData.length-1
                    ? 'rgba(0,212,170,.85)'
                    : 'rgba(0,212,170,.2)'
            ),
            borderRadius: 8,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(255,255,255,.04)' },
                ticks: { color: '#6b7a99', font: { family: 'DM Sans' } }
            },
            x: {
                grid: { display: false },
                ticks: { color: '#6b7a99', font: { family: 'DM Sans' } }
            }
        }
    }
});

document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
</script>
@endpush
