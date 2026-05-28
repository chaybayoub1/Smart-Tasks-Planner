{{-- resources/views/exams/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Exams')
@section('page-title', 'Exams')

@section('content')
<div class="row g-4">

    {{-- ── ADD EXAM FORM ─────────────────────────────────────── --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <i class="bi bi-calendar-plus-fill text-danger me-2"></i>
                <span>Add Exam</span>
                <button class="btn btn-sm btn-light ms-auto" type="button"
                        data-bs-toggle="collapse" data-bs-target="#addExamForm">
                    <i class="bi bi-chevron-down"></i>
                </button>
            </div>
            <div class="collapse" id="addExamForm">
                <div class="card-body">
                    <form method="POST" action="{{ route('exams.store') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-500 small">Exam Title *</label>
                                <input type="text" name="title" class="form-control"
                                       placeholder="e.g. Calculus Midterm"
                                       value="{{ old('title') }}" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-500 small">Subject</label>
                                <select name="subject_id" class="form-select">
                                    <option value="">— None —</option>
                                    @foreach($subjects as $s)
                                        <option value="{{ $s->id }}"
                                            {{ old('subject_id') == $s->id ? 'selected' : '' }}>
                                            {{ $s->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-500 small">Date & Time *</label>
                                <input type="datetime-local" name="exam_date" class="form-control"
                                       value="{{ old('exam_date') }}" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-500 small">Location</label>
                                <input type="text" name="location" class="form-control"
                                       placeholder="Room / Online"
                                       value="{{ old('location') }}">
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button class="btn btn-danger w-100">Add</button>
                            </div>
                            <div class="col-12">
                                <textarea name="notes" class="form-control form-control-sm" rows="2"
                                          placeholder="Study notes / topics to cover (optional)">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ── UPCOMING EXAMS ────────────────────────────────────── --}}
    <div class="col-12">
        <h5 class="fw-700 mb-3">
            <i class="bi bi-alarm text-danger me-2"></i>
            Upcoming Exams
            <span class="badge text-bg-danger ms-2">{{ $upcoming->count() }}</span>
        </h5>

        @forelse($upcoming as $exam)
        <div class="card mb-3 border-{{ $exam->urgencyClass() }} border-opacity-40">
            <div class="card-body">
                <div class="row align-items-center">

                    {{-- Countdown block --}}
                    <div class="col-auto text-center px-4">
                        <div class="fw-800 text-{{ $exam->urgencyClass() }}"
                             style="font-size:2.8rem;line-height:1">
                            {{ $exam->daysUntil() }}
                        </div>
                        <div class="text-muted small fw-500">
                            {{ $exam->daysUntil() == 1 ? 'day' : 'days' }}
                        </div>
                    </div>

                    {{-- Details --}}
                    <div class="col">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <h6 class="fw-700 mb-0">{{ $exam->title }}</h6>
                            <span class="badge text-bg-{{ $exam->urgencyClass() }}">
                                {{ $exam->daysUntil() === 0 ? '🚨 TODAY' : ($exam->daysUntil() <= 3 ? '🔥 Soon' : '📅 Upcoming') }}
                            </span>
                            @if($exam->subject)
                                <span class="badge"
                                      style="background:{{ $exam->subject->color }}20;
                                             color:{{ $exam->subject->color }}">
                                    {{ $exam->subject->name }}
                                </span>
                            @endif
                        </div>
                        <div class="text-muted small mt-1 d-flex gap-3 flex-wrap">
                            <span><i class="bi bi-calendar3 me-1"></i>{{ $exam->exam_date->format('M d, Y') }}</span>
                            <span><i class="bi bi-clock me-1"></i>{{ $exam->exam_date->format('H:i') }}</span>
                            @if($exam->location)
                                <span><i class="bi bi-geo-alt me-1"></i>{{ $exam->location }}</span>
                            @endif
                        </div>
                        @if($exam->notes)
                            <div class="mt-2 p-2 rounded small"
                                 style="background:#f8f9ff;border-left:3px solid #6366f1">
                                {{ $exam->notes }}
                            </div>
                        @endif
                    </div>

                    {{-- Live countdown --}}
                    <div class="col-auto text-center d-none d-md-block px-3">
                        <div class="countdown-timer text-muted small fw-600"
                             data-date="{{ $exam->exam_date->toISOString() }}">
                            —
                        </div>
                        <div class="text-muted" style="font-size:.65rem">LIVE COUNTDOWN</div>
                    </div>

                    {{-- Actions --}}
                    <div class="col-auto">
                        <a href="{{ route('exams.edit', $exam) }}"
                           class="btn btn-sm btn-light me-1">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('exams.destroy', $exam) }}"
                              class="d-inline" onsubmit="return confirm('Delete exam?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-light text-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="card">
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-calendar-check fs-1 d-block mb-3 text-secondary"></i>
                <h5>No upcoming exams</h5>
                <p class="small">Add an exam above to start your countdown!</p>
            </div>
        </div>
        @endforelse
    </div>

    {{-- ── PAST EXAMS ────────────────────────────────────────── --}}
    @if($past->count())
    <div class="col-12">
        <h5 class="fw-700 mb-3 text-muted">
            <i class="bi bi-archive me-2"></i>Past Exams
        </h5>
        <div class="card">
            <div class="list-group list-group-flush">
                @foreach($past as $exam)
                <div class="list-group-item d-flex align-items-center gap-3">
                    <span class="badge text-bg-secondary">Done</span>
                    <div class="flex-grow-1">
                        <span class="fw-500">{{ $exam->title }}</span>
                        @if($exam->subject)
                            <span class="text-muted small ms-2">· {{ $exam->subject->name }}</span>
                        @endif
                    </div>
                    <small class="text-muted">{{ $exam->exam_date->format('M d, Y') }}</small>
                    <form method="POST" action="{{ route('exams.destroy', $exam) }}"
                          onsubmit="return confirm('Delete?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-light py-0 text-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
// Live countdown timers
function updateCountdowns() {
    document.querySelectorAll('.countdown-timer').forEach(el => {
        const target = new Date(el.dataset.date);
        const diff   = target - new Date();
        if (diff <= 0) { el.textContent = '🔔 Now!'; return; }
        const d = Math.floor(diff / 86400000);
        const h = Math.floor((diff % 86400000) / 3600000);
        const m = Math.floor((diff % 3600000) / 60000);
        const s = Math.floor((diff % 60000) / 1000);
        el.textContent = d > 0
            ? `${d}d ${h}h ${m}m`
            : `${h}h ${m}m ${s}s`;
    });
}
updateCountdowns();
setInterval(updateCountdowns, 1000);
</script>
@endpush
