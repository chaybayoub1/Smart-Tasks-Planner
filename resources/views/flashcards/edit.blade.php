{{-- resources/views/flashcards/edit.blade.php --}}
@extends('layouts.app')
@section('title', 'Edit Flashcard')
@section('page-title', 'Edit Flashcard')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-pencil-fill text-primary me-2"></i>Edit Flashcard
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('flashcards.update', $flashcard) }}">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-500">Subject</label>
                        <select name="subject_id" class="form-select">
                            <option value="">— None —</option>
                            @foreach($subjects as $s)
                                <option value="{{ $s->id }}"
                                    {{ old('subject_id', $flashcard->subject_id) == $s->id ? 'selected' : '' }}>
                                    {{ $s->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-500">Question / Front *</label>
                        <textarea name="question" class="form-control" rows="4" required>{{ old('question', $flashcard->question) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-500">Answer / Back *</label>
                        <textarea name="answer" class="form-control" rows="4" required>{{ old('answer', $flashcard->answer) }}</textarea>
                    </div>

                    {{-- Stats --}}
                    <div class="alert alert-light d-flex gap-4 py-2 mb-4">
                        <span><strong>Reviews:</strong> {{ $flashcard->review_count }}</span>
                        <span><strong>Difficulty:</strong>
                            <span class="badge text-bg-{{ $flashcard->difficultyColor() }}">
                                {{ $flashcard->difficulty }}
                            </span>
                        </span>
                        @if($flashcard->next_review_at)
                        <span><strong>Next review:</strong> {{ $flashcard->next_review_at->diffForHumans() }}</span>
                        @endif
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-primary">Save Changes</button>
                        <a href="{{ route('flashcards.index') }}" class="btn btn-light">Cancel</a>
                        <form method="POST" action="{{ route('flashcards.destroy', $flashcard) }}"
                              class="ms-auto" onsubmit="return confirm('Delete this card?')">
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
