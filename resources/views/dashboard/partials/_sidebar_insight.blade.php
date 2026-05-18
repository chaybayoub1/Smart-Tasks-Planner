{{--
    resources/views/dashboard/partials/_sidebar_insight.blade.php

    Purpose  : Right-side activity sidebar containing three stacked cards:
                 1. Alerts — overdue tasks, flashcards due, upcoming exams
                 2. XP / Level progress bar
                 3. Earned badges
    Included : dashboard/partials/_activity_row.blade.php
    Data     : $taskStats        (array)
               $flashcardsDue    (int)
               $upcomingExams    (Collection<Exam>)  — exam exposes urgencyClass(), daysUntil()
               $totalNotes       (int)
               $totalFlashcards  (int)
               $user             (model) — exposes level, xp, xpForNextLevel(), xpProgress()
               $badges           (Collection<Badge>) — badge exposes icon, name, description
--}}

{{-- Alerts --}}
<div class="notif-card">
    <div class="notif-card-header">
        <span><i class="bi bi-bell-fill me-2"></i>Alerts</span>
        <span style="font-size:.7rem; color:var(--db-muted); font-weight:400; text-transform:none; letter-spacing:0;">
            <i class="bi bi-journal-text me-1"></i>{{ $totalNotes }} Notes &nbsp;·&nbsp;
            <i class="bi bi-layers me-1"></i>{{ $totalFlashcards }} Cards
        </span>
    </div>
    <ul class="list-group list-group-flush">
        @if(($taskStats['overdue'] ?? 0) > 0)
        <li class="list-group-item d-flex align-items-center gap-2" style="color:var(--db-rose); font-size:.82rem;">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span>
                <strong>{{ $taskStats['overdue'] }}</strong> overdue task{{ $taskStats['overdue'] > 1 ? 's' : '' }}
                <span class="overdue-badge ms-1">{{ $taskStats['overdue_percentage'] ?? 0 }}% of total</span>
            </span>
            <a href="{{ route('tasks.index', ['status'=>'pending']) }}" class="ms-auto btn btn-sm btn-outline-danger py-0" style="font-size:.72rem;">View</a>
        </li>
        @endif

        @if($flashcardsDue > 0)
        <li class="list-group-item d-flex align-items-center gap-2" style="color:var(--db-sky); font-size:.82rem;">
            <i class="bi bi-layers-fill"></i>
            <span><strong>{{ $flashcardsDue }}</strong> flashcard{{ $flashcardsDue > 1 ? 's' : '' }} due</span>
            <a href="{{ route('flashcards.review') }}" class="ms-auto btn btn-sm btn-outline-info py-0" style="font-size:.72rem;">Review</a>
        </li>
        @endif

        @foreach($upcomingExams->take(2) as $exam)
        <li class="list-group-item d-flex align-items-center gap-2" style="font-size:.82rem;">
            <i class="bi bi-calendar-event text-{{ $exam->urgencyClass() }}"></i>
            <span><strong>{{ $exam->title }}</strong> in {{ $exam->daysUntil() }} day{{ $exam->daysUntil() != 1 ? 's' : '' }}</span>
        </li>
        @endforeach

        @if(($taskStats['overdue'] ?? 0) === 0 && $flashcardsDue === 0 && $upcomingExams->isEmpty())
        <li class="list-group-item text-center py-4" style="color:var(--db-muted); font-size:.83rem;">
            <i class="bi bi-check-circle-fill text-success fs-3 d-block mb-2"></i>
            All caught up! 🎉
        </li>
        @endif
    </ul>
</div>

{{-- XP / Level --}}
<div class="xp-card">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <p style="font-size:.68rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; opacity:.7; margin:0 0 2px;">Level Progress</p>
            <p style="font-size:1.6rem; font-weight:800; margin:0; letter-spacing:-.03em;">Level {{ $user->level }} ⭐</p>
        </div>
        <div style="text-align:right;">
            <p style="font-size:.75rem; opacity:.8; margin:0;">{{ number_format($user->xp) }} XP</p>
            <p style="font-size:.68rem; opacity:.6; margin:0;">→ {{ number_format($user->xpForNextLevel()) }} next</p>
        </div>
    </div>
    <div class="xp-bar-track">
        <div class="xp-bar-fill" style="width:{{ $user->xpProgress() }}%"></div>
    </div>
    <p style="font-size:.7rem; opacity:.65; margin:0;">{{ $user->xpProgress() }}% to next level</p>
</div>

{{-- Badges --}}
<div class="notif-card flex-grow-1">
    <div class="notif-card-header">
        <span><i class="bi bi-award-fill me-2"></i>Badges</span>
    </div>
    <div class="p-3">
        @if($badges->isEmpty())
            <p style="font-size:.8rem; color:var(--db-muted); margin:0;">Complete sessions to earn badges!</p>
        @else
        <div class="d-flex flex-wrap gap-2">
            @foreach($badges as $badge)
            <div class="badge-chip" title="{{ $badge->name }}: {{ $badge->description }}" data-bs-toggle="tooltip">
                <span class="badge-chip-icon">{{ $badge->icon }}</span>
                <span class="badge-chip-name">{{ $badge->name }}</span>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
