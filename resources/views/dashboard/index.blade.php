{{-- resources/views/dashboard/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- ── STAT CARDS ──────────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#6366f1,#8b5cf6)">
            <div class="stat-value">{{ floor($totalMinutes / 60) }}h {{ $totalMinutes % 60 }}m</div>
            <div class="stat-label">Total Study Time</div>
            <i class="bi bi-clock-fill stat-icon"></i>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#f59e0b,#ef4444)">
            <div class="stat-value">{{ $streak->current_streak ?? 0 }}</div>
            <div class="stat-label">Day Streak 🔥</div>
            <i class="bi bi-fire stat-icon"></i>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#10b981,#059669)">
            <div class="stat-value">{{ $totalSessions }}</div>
            <div class="stat-label">Pomodoro Sessions</div>
            <i class="bi bi-stopwatch-fill stat-icon"></i>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#3b82f6,#1d4ed8)">
            <div class="stat-value">{{ $user->level }}</div>
            <div class="stat-label">Current Level ⭐</div>
            <i class="bi bi-star-fill stat-icon"></i>
        </div>
    </div>
</div>

{{-- ── ROW 2 ────────────────────────────────────────────────── --}}
<div class="row g-3 mb-4">

    {{-- Weekly Chart --}}
    <div class="col-md-7">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-bar-chart-fill text-primary me-2"></i>Weekly Study Minutes</span>
                <span class="badge text-bg-light text-muted small">Last 7 days</span>
            </div>
            <div class="card-body">
                <canvas id="weeklyChart" height="90"></canvas>
            </div>
        </div>
    </div>

    {{-- Quick stats + alerts --}}
    <div class="col-md-5">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-bell-fill text-warning me-2"></i>Notifications</div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @if($overdueTasks > 0)
                    <li class="list-group-item d-flex align-items-center gap-2 text-danger">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <span><strong>{{ $overdueTasks }}</strong> overdue task{{ $overdueTasks > 1 ? 's' : '' }}</span>
                        <a href="{{ route('tasks.index', ['status'=>'pending']) }}" class="ms-auto btn btn-sm btn-outline-danger py-0">View</a>
                    </li>
                    @endif

                    @if($flashcardsDue > 0)
                    <li class="list-group-item d-flex align-items-center gap-2 text-info">
                        <i class="bi bi-layers-fill"></i>
                        <span><strong>{{ $flashcardsDue }}</strong> flashcard{{ $flashcardsDue > 1 ? 's' : '' }} due</span>
                        <a href="{{ route('flashcards.review') }}" class="ms-auto btn btn-sm btn-outline-info py-0">Review</a>
                    </li>
                    @endif

                    @foreach($upcomingExams->take(2) as $exam)
                    <li class="list-group-item d-flex align-items-center gap-2">
                        <i class="bi bi-calendar-event text-{{ $exam->urgencyClass() }}"></i>
                        <span class="small"><strong>{{ $exam->title }}</strong> in {{ $exam->daysUntil() }} day{{ $exam->daysUntil() != 1 ? 's' : '' }}</span>
                    </li>
                    @endforeach

                    @if($overdueTasks === 0 && $flashcardsDue === 0 && $upcomingExams->isEmpty())
                    <li class="list-group-item text-muted text-center py-4">
                        <i class="bi bi-check-circle-fill text-success fs-3 d-block mb-2"></i>
                        All caught up! 🎉
                    </li>
                    @endif

                    <li class="list-group-item text-center">
                        <small class="text-muted">
                            <i class="bi bi-journal-text me-1"></i>{{ $totalNotes }} Notes &nbsp;·&nbsp;
                            <i class="bi bi-layers me-1"></i>{{ $totalFlashcards }} Flashcards
                        </small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- ── ROW 3 ────────────────────────────────────────────────── --}}
<div class="row g-3 mb-4">

    {{-- Upcoming Tasks --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-check2-square text-primary me-2"></i>Upcoming Tasks</span>
                <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-outline-primary py-0">All Tasks</a>
            </div>
            <div class="card-body p-0">
                @forelse($upcomingTasks as $task)
                <div class="d-flex align-items-center gap-3 px-3 py-2 border-bottom">
                    <form method="POST" action="{{ route('tasks.toggle', $task) }}">
                        @csrf @method('PATCH')
                        <button class="btn btn-sm p-0 border-0 text-{{ $task->status === 'completed' ? 'success' : 'secondary' }}">
                            <i class="bi bi-{{ $task->status === 'completed' ? 'check-circle-fill' : 'circle' }} fs-5"></i>
                        </button>
                    </form>
                    <div class="flex-grow-1 min-w-0">
                        <div class="fw-500 text-truncate {{ $task->status === 'completed' ? 'text-decoration-line-through text-muted' : '' }}">
                            {{ $task->title }}
                        </div>
                        <small class="text-muted">
                            @if($task->subject)
                                <span class="badge" style="background:{{ $task->subject->color }}20;color:{{ $task->subject->color }}">{{ $task->subject->name }}</span>
                            @endif
                            {{ $task->due_date->format('M d') }} · {{ $task->duration }}min
                        </small>
                    </div>
                    <span class="badge text-bg-{{ $task->priorityBadgeClass() }} badge-sm">{{ $task->priority }}</span>
                </div>
                @empty
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-calendar-check fs-2 d-block mb-2"></i>
                    No upcoming tasks. <a href="{{ route('tasks.index') }}">Add one!</a>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- XP + Level + Badges --}}
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div>
                        <h6 class="mb-0 fw-bold">Level {{ $user->level }}</h6>
                        <small class="text-muted">{{ number_format($user->xp) }} / {{ number_format($user->xpForNextLevel()) }} XP to next level</small>
                    </div>
                    <div class="text-warning fs-2">⭐</div>
                </div>
                <div class="progress" style="height:10px; border-radius:99px;">
                    <div class="progress-bar" style="width:{{ $user->xpProgress() }}%; background:linear-gradient(90deg,#6366f1,#8b5cf6); border-radius:99px;"></div>
                </div>
                <div class="d-flex justify-content-between mt-1">
                    <small class="text-muted">{{ $user->xpProgress() }}%</small>
                    <small class="text-muted">Best streak: {{ $streak->longest_streak ?? 0 }} days</small>
                </div>
            </div>
        </div>

        {{-- Badges --}}
        <div class="card">
            <div class="card-header"><i class="bi bi-award-fill text-warning me-2"></i>Badges</div>
            <div class="card-body">
                @if($badges->isEmpty())
                    <p class="text-muted small mb-0">Complete sessions to earn badges!</p>
                @else
                <div class="d-flex flex-wrap gap-2">
                    @foreach($badges as $badge)
                    <div class="text-center" title="{{ $badge->name }}: {{ $badge->description }}" data-bs-toggle="tooltip">
                        <div class="fs-2" style="line-height:1">{{ $badge->icon }}</div>
                        <div style="font-size:.65rem; color:#666; max-width:60px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis">{{ $badge->name }}</div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ── QUICK ACTIONS ────────────────────────────────────────── --}}
<div class="card">
    <div class="card-body">
        <h6 class="fw-semibold mb-3"><i class="bi bi-lightning-fill text-warning me-2"></i>Quick Actions</h6>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('pomodoro.index') }}" class="btn btn-primary">
                <i class="bi bi-stopwatch me-1"></i> Start Pomodoro
            </a>
            <a href="{{ route('notes.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-plus-lg me-1"></i> New Note
            </a>
            <a href="{{ route('flashcards.review') }}" class="btn btn-outline-info">
                <i class="bi bi-layers me-1"></i> Review Cards
            </a>
            <a href="{{ route('tasks.index') }}" class="btn btn-outline-success">
                <i class="bi bi-calendar-plus me-1"></i> Plan Task
            </a>
            <a href="{{ route('exams.index') }}" class="btn btn-outline-danger">
                <i class="bi bi-journal-check me-1"></i> Add Exam
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
        labels: weeklyData.map(d => {
            const date = new Date(d.date);
            return date.toLocaleDateString('en', {weekday:'short'});
        }),
        datasets: [{
            label: 'Minutes',
            data: weeklyData.map(d => d.minutes),
            backgroundColor: weeklyData.map((d,i) =>
                i === weeklyData.length-1
                    ? 'rgba(99,102,241,0.9)'
                    : 'rgba(99,102,241,0.35)'
            ),
            borderRadius: 8,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color:'rgba(0,0,0,.04)' }, ticks:{color:'#999'} },
            x: { grid: { display: false }, ticks:{color:'#999'} }
        }
    }
});

// Tooltips
document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
    new bootstrap.Tooltip(el);
});
</script>
@endpush
