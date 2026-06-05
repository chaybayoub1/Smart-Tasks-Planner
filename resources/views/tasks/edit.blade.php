{{-- resources/views/tasks/edit.blade.php --}}
@extends('layouts.app')
@section('title', 'Edit Task')
@section('page-title', 'Edit Task')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header"><i class="bi bi-pencil-fill text-primary me-2"></i>Edit Task</div>
            <div class="card-body">
                <form method="POST" action="{{ route('tasks.update', $task) }}">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-500">Title *</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $task->title) }}" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-500">Subject</label>
                            <select name="subject_id" class="form-select">
                                <option value="">— None —</option>
                                @foreach($subjects as $s)
                                    <option value="{{ $s->id }}" {{ old('subject_id', $task->subject_id) == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-500">Due Date *</label>
                            <input type="date" name="due_date" class="form-control" value="{{ old('due_date', $task->due_date->toDateString()) }}" required>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-500">Priority</label>
                            <select name="priority" class="form-select">
                                @foreach(['low','medium','high'] as $p)
                                    <option value="{{ $p }}" {{ old('priority', $task->priority) === $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-500">Status</label>
                            <select name="status" class="form-select">
                                @foreach(['pending','in_progress','completed'] as $s)
                                    <option value="{{ $s }}" {{ old('status', $task->status) === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-500">Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Optional details...">{{ old('description', $task->description) }}</textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary">Save Changes</button>
                        <a href="{{ route('tasks.index') }}" class="btn btn-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
