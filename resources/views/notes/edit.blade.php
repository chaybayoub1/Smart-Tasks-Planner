{{-- resources/views/notes/edit.blade.php --}}
@extends('layouts.app')
@section('title', 'Edit Note')
@section('page-title', 'Edit Note')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-pencil-fill text-primary me-2"></i>Edit Note
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('notes.update', $note) }}">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-500">Title *</label>
                        <input type="text" name="title" class="form-control"
                               value="{{ old('title', $note->title) }}" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-500">Subject</label>
                            <select name="subject_id" class="form-select">
                                <option value="">— None —</option>
                                @foreach($subjects as $s)
                                    <option value="{{ $s->id }}"
                                        {{ old('subject_id', $note->subject_id) == $s->id ? 'selected' : '' }}>
                                        {{ $s->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="form-check mb-1">
                                <input class="form-check-input" type="checkbox"
                                       name="is_pinned" id="is_pinned" value="1"
                                       {{ old('is_pinned', $note->is_pinned) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_pinned">
                                    📌 Pin this note
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-500">Content *</label>
                        <textarea name="content" class="form-control" rows="14" required>{{ old('content', $note->content) }}</textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Save Changes
                        </button>
                        <a href="{{ route('notes.index') }}" class="btn btn-light">Cancel</a>
                        <form method="POST" action="{{ route('notes.destroy', $note) }}"
                              class="ms-auto" onsubmit="return confirm('Delete this note?')">
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
