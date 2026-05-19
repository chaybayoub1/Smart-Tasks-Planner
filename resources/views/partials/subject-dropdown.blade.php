{{--
    Reusable subject dropdown partial.

    Usage in task create/edit forms:
        @include('partials.subject-dropdown', ['selected' => $task->subject_id ?? null])

    Usage in Pomodoro start form:
        @include('partials.subject-dropdown', ['selected' => session('active_subject_id')])
--}}

@php
    // Load user's subjects if not already passed from controller
    $dropdownSubjects = $dropdownSubjects ?? auth()->user()->subjects()->orderBy('name')->get();
@endphp

<div class="mb-3">
    <label for="subject_id" class="form-label">
        Subject
        @if(!isset($required))
            <span class="text-muted small">(optional)</span>
        @endif
    </label>

    <select
        name="subject_id"
        id="subject_id"
        class="form-select @error('subject_id') is-invalid @enderror"
    >
        <option value="">— No subject —</option>

        @foreach($dropdownSubjects as $subject)
            <option
                value="{{ $subject->id }}"
                {{ (string)($selected ?? '') === (string)$subject->id ? 'selected' : '' }}
                data-color="{{ $subject->color ?? '#6366f1' }}"
            >
                {{ $subject->name }}
            </option>
        @endforeach
    </select>

    @error('subject_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror

    @if($dropdownSubjects->isEmpty())
        <div class="form-text text-warning">
            📚 No subjects yet.
            <a href="{{ route('subjects.create') }}">Create a subject</a>
            to unlock detailed analytics.
        </div>
    @else
        <div class="form-text text-muted">
            Linking to a subject enables productivity analytics and study-time tracking.
        </div>
    @endif
</div>
