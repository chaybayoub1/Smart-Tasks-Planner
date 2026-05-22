{{-- resources/views/exams/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Exams')
@section('page-title', 'Exams')

@section('content')
<div class="row g-4">

    {{-- ── ADD EXAM FORM ─────────────────────────────────────── --}}
    <div class="col-12">
        <div class="st-card">
            <div class="st-card-header">
                <div class="st-card-title">
                    <div class="icon icon-coral"><i class="bi bi-calendar-plus-fill"></i></div>
                    Add Exam
                </div>
                <button type="button" class="btn-st-ghost" data-bs-toggle="collapse" data-bs-target="#addExamForm" style="font-size:.75rem;padding:.3rem .65rem">
                    <i class="bi bi-chevron-down"></i>
                </button>
            </div>
            <div class="collapse" id="addExamForm">
                <div style="padding:1.25rem">
                    <form method="POST" action="{{ route('exams.store') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="st-label">Exam Title *</label>
                                <input type="text" name="title" class="st-input" placeholder="e.g. Calculus Midterm" value="{{ old('title') }}" required>
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
                            <div class="col-md-3">
                                <label class="st-label">Date & Time *</label>
                                <input type="datetime-local" name="exam_date" class="st-input" value="{{ old('exam_date') }}" required>
                            </div>
                            <div class="col-md-2">
                                <label class="st-label">Location</label>
                                <input type="text" name="location" class="st-input" placeholder="Room / Online" value="{{ old('location') }}">
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button class="btn-st-danger" style="width:100%;justify-content:center;background:rgba(255,107,107,.15);border:1px solid rgba(255,107,107,.3)">Add</button>
                            </div>
                            <div class="col-12">
                                <textarea name="notes" class="st-textarea" rows="2" placeholder="Study notes / topics to cover (optional)">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ── UPCOMING EXAMS ────────────────────────────────────── --}}
    <div class="col-12">
        <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:1rem">
            <div class="section-title" style="margin:0">Upcoming Exams</div>
            <span class="tag tag-coral" style="font-size:.72rem">{{ $upcoming->count() }}</span>
        </div>

        @forelse($upcoming as $exam)
        @php
            $days = $exam->daysUntil();
            $urgColor = $days === 0 ? 'coral' : ($days <= 3 ? 'amber' : 'teal');
            $urgBorder = $days === 0 ? 'rgba(255,107,107,.35)' : ($days <= 3 ? 'rgba(245,158,11,.35)' : 'rgba(0,212,170,.2)');
        @endphp
        <div class="st-card" style="margin-bottom:.75rem;border-color:{{ $urgBorder }}">
            <div style="padding:1.1rem 1.25rem">
                <div class="row align-items-center g-3">

                    {{-- Countdown block --}}
                    <div class="col-auto">
                        <div style="min-width:70px;text-align:center;padding:.5rem .75rem;border-radius:var(--radius-md);background:rgba(255,255,255,.04);border:1px solid var(--c-border)">
                            <div style="font-family:var(--font-display);font-size:2.2rem;font-weight:800;line-height:1;color:var(--c-{{ $urgColor }})">{{ $days }}</div>
                            <div style="font-size:.68rem;color:var(--c-muted);text-transform:uppercase;letter-spacing:.07em">{{ $days == 1 ? 'day' : 'days' }}</div>
                        </div>
                    </div>

                    {{-- Details --}}
                    <div class="col">
                        <div style="display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;margin-bottom:.35rem">
                            <span style="font-weight:700;font-size:.95rem;color:var(--c-text)">{{ $exam->title }}</span>
                            <span class="tag tag-{{ $urgColor }}" style="font-size:.68rem">
                                {{ $days === 0 ? 'TODAY' : ($days <= 3 ? 'Soon' : 'Upcoming') }}
                            </span>
                            @if($exam->subject)
                                <span class="tag" style="background:{{ $exam->subject->color }}15;color:{{ $exam->subject->color }};border-color:{{ $exam->subject->color }}30;font-size:.68rem">
                                    {{ $exam->subject->name }}
                                </span>
                            @endif
                        </div>
                        <div style="display:flex;gap:.85rem;flex-wrap:wrap">
                            <span style="font-size:.78rem;color:var(--c-muted)">
                                <i class="bi bi-calendar3 me-1"></i>{{ $exam->exam_date->format('M d, Y') }}
                            </span>
                            <span style="font-size:.78rem;color:var(--c-muted)">
                                <i class="bi bi-clock me-1"></i>{{ $exam->exam_date->format('H:i') }}
                            </span>
                            @if($exam->location)
                                <span style="font-size:.78rem;color:var(--c-muted)">
                                    <i class="bi bi-geo-alt me-1"></i>{{ $exam->location }}
                                </span>
                            @endif
                        </div>
                        @if($exam->notes)
                            <div style="margin-top:.6rem;padding:.5rem .75rem;border-left:2px solid var(--c-teal);background:rgba(0,212,170,.05);border-radius:0 6px 6px 0;font-size:.8rem;color:var(--c-muted2)">
                                {{ $exam->notes }}
                            </div>
                        @endif
                    </div>

                    {{-- Live countdown --}}
                    <div class="col-auto d-none d-md-block" style="text-align:center">
                        <div class="countdown-timer" data-date="{{ $exam->exam_date->toISOString() }}"
                             style="font-family:var(--font-display);font-size:1rem;font-weight:700;color:var(--c-{{ $urgColor }});min-width:80px">—</div>
                        <div style="font-size:.62rem;color:var(--c-muted);text-transform:uppercase;letter-spacing:.08em;margin-top:.2rem">live</div>
                    </div>

                    {{-- Actions --}}
                    <div class="col-auto" style="display:flex;gap:.4rem">
                        <a href="{{ route('exams.edit', $exam) }}" class="btn-st-ghost" style="padding:.4rem .65rem;font-size:.85rem">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('exams.destroy', $exam) }}" onsubmit="return confirm('Delete exam?')">
                            @csrf @method('DELETE')
                            <button class="btn-st-danger" style="padding:.4rem .65rem;font-size:.85rem">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        @empty
        <div class="st-card">
            <div class="empty-state">
                <div class="empty-state-icon"><i class="bi bi-calendar-check"></i></div>
                <p>No upcoming exams.<br><a href="#addExamForm" onclick="document.getElementById('addExamForm').classList.add('show')">Add one to start your countdown!</a></p>
            </div>
        </div>
        @endforelse
    </div>

    {{-- ── PAST EXAMS ────────────────────────────────────────── --}}
    @if($past->count())
    <div class="col-12">
        <div style="font-family:var(--font-display);font-size:.85rem;font-weight:700;color:var(--c-muted);text-transform:uppercase;letter-spacing:.08em;margin-bottom:.75rem">
            <i class="bi bi-archive me-2"></i>Past Exams
        </div>
        <div class="st-card">
            @foreach($past as $exam)
            <div class="st-list-item">
                <span class="tag tag-muted">Done</span>
                <div style="flex:1;min-width:0">
                    <span style="font-size:.88rem;font-weight:500;color:var(--c-text)">{{ $exam->title }}</span>
                    @if($exam->subject)
                        <span style="font-size:.78rem;color:var(--c-muted);margin-left:.5rem">&middot; {{ $exam->subject->name }}</span>
                    @endif
                </div>
                <span style="font-size:.75rem;color:var(--c-muted)">{{ $exam->exam_date->format('M d, Y') }}</span>
                <form method="POST" action="{{ route('exams.destroy', $exam) }}" onsubmit="return confirm('Delete?')">
                    @csrf @method('DELETE')
                    <button class="btn-st-danger" style="padding:.3rem .55rem;font-size:.8rem">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
function updateCountdowns() {
    document.querySelectorAll('.countdown-timer').forEach(el => {
        const diff = new Date(el.dataset.date) - new Date();
        if (diff <= 0) { el.textContent = 'Now!'; return; }
        const d = Math.floor(diff / 86400000);
        const h = Math.floor((diff % 86400000) / 3600000);
        const m = Math.floor((diff % 3600000) / 60000);
        const s = Math.floor((diff % 60000) / 1000);
        el.textContent = d > 0 ? `${d}d ${h}h ${m}m` : `${h}h ${m}m ${s}s`;
    });
}
updateCountdowns();
setInterval(updateCountdowns, 1000);
</script>
@endpush
