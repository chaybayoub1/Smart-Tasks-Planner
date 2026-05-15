{{-- resources/views/exams/edit.blade.php --}}
@extends('layouts.app')
@section('title', 'Edit Exam')
@section('page-title', 'Edit Exam')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-pencil-fill text-danger me-2"></i>Edit Exam
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('exams.update', $exam) }}">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-500">Exam Title *</label>
                        <input type="text" name="title" class="form-control"
                               value="{{ old('title', $exam->title) }}" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-500">Subject</label>
                            <select name="subject_id" class="form-select">
                                <option value="">— None —</option>
                                @foreach($subjects as $s)
                                    <option value="{{ $s->id }}"
                                        {{ old('subject_id', $exam->subject_id) == $s->id ? 'selected' : '' }}>
                                        {{ $s->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-500">Date & Time *</label>
                            <input type="datetime-local" name="exam_date" class="form-control"
                                   value="{{ old('exam_date', $exam->exam_date->format('Y-m-d\TH:i')) }}"
                                   required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-500">Location</label>
                        <input type="text" name="location" class="form-control"
                               placeholder="Room 101, Online, etc."
                               value="{{ old('location', $exam->location) }}">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-500">Study Notes</label>
                        <textarea name="notes" class="form-control" rows="4"
                                  placeholder="Topics to cover, reminders…">{{ old('notes', $exam->notes) }}</textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-danger">Save Changes</button>
                        <a href="{{ route('exams.index') }}" class="btn btn-light">Cancel</a>
                        <form method="POST" action="{{ route('exams.destroy', $exam) }}"
                              class="ms-auto" onsubmit="return confirm('Delete this exam?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline-danger">
                                <i class="bi bi-trash me-1"></i>Delete
                            </button>
                        </form>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
