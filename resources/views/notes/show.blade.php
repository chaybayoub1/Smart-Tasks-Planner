{{-- resources/views/notes/show.blade.php --}}
@extends('layouts.app')
@section('title', $note->title)
@section('page-title', 'Note')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    @if($note->is_pinned)
                        <span class="me-1">📌</span>
                    @endif
                    @if($note->subject)
                        <span class="badge me-2"
                              style="background:{{ $note->subject->color }}20;
                                     color:{{ $note->subject->color }}">
                            {{ $note->subject->name }}
                        </span>
                    @endif
                    <small class="text-muted">
                        Last updated {{ $note->updated_at->format('M d, Y · H:i') }}
                    </small>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('notes.edit', $note) }}"
                       class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                    <a href="{{ route('notes.index') }}"
                       class="btn btn-sm btn-light">← Back</a>
                </div>
            </div>
            <div class="card-body">
                <h2 class="fw-700 mb-4">{{ $note->title }}</h2>
                <div class="note-content" style="line-height:1.9; font-size:1rem; white-space:pre-wrap; word-wrap:break-word;">{{ $note->content }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
