{{-- resources/views/subjects/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Subjects')
@section('page-title', 'Subjects')

@section('content')
<div class="row g-4">

    {{-- ── ADD SUBJECT FORM ──────────────────────────────────── --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <i class="bi bi-plus-circle-fill text-primary me-2"></i>
                <span>Add New Subject</span>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('subjects.store') }}">
                    @csrf
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label fw-500 small">Subject Name *</label>
                            <input type="text" name="name" class="form-control"
                                   placeholder="e.g. Mathematics"
                                   value="{{ old('name') }}" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-500 small">Colour</label>
                            <div class="d-flex align-items-center gap-2">
                                <input type="color" name="color" class="form-control form-control-color"
                                       value="{{ old('color', '#6366f1') }}" style="width:50px;height:38px">
                                <input type="text" id="colorHex" class="form-control form-control-sm"
                                       value="{{ old('color', '#6366f1') }}" readonly style="width:80px">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-500 small">Description</label>
                            <input type="text" name="description" class="form-control"
                                   placeholder="Optional description"
                                   value="{{ old('description') }}">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100">
                                <i class="bi bi-plus me-1"></i>Add Subject
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── SUBJECT CARDS ─────────────────────────────────────── --}}
    <div class="col-12">
        <div class="row g-3">
            @forelse($subjects as $subject)
            <div class="col-md-4 col-sm-6">
                <div class="card h-100">
                    {{-- Color bar --}}
                    <div style="height:6px;background:{{ $subject->color }};
                                border-radius:12px 12px 0 0"></div>
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between mb-2">
                            <div>
                                <h6 class="fw-700 mb-0">{{ $subject->name }}</h6>
                                @if($subject->description)
                                    <small class="text-muted">{{ $subject->description }}</small>
                                @endif
                            </div>
                            <span class="rounded-circle d-inline-block ms-2 flex-shrink-0"
                                  style="width:18px;height:18px;background:{{ $subject->color }};
                                         border:2px solid #fff;box-shadow:0 0 0 2px {{ $subject->color }}44">
                            </span>
                        </div>

                        {{-- Stats --}}
                        <div class="d-flex gap-3 my-3 text-center">
                            <div>
                                <div class="fw-700 text-primary">{{ $subject->tasks_count }}</div>
                                <div class="text-muted" style="font-size:.7rem">Tasks</div>
                            </div>
                            <div>
                                <div class="fw-700 text-success">{{ $subject->notes_count }}</div>
                                <div class="text-muted" style="font-size:.7rem">Notes</div>
                            </div>
                            <div>
                                <div class="fw-700 text-info">{{ $subject->flashcards_count }}</div>
                                <div class="text-muted" style="font-size:.7rem">Cards</div>
                            </div>
                        </div>

                        {{-- Edit form (inline collapse) --}}
                        <div class="d-flex gap-1 justify-content-end">
                            <button class="btn btn-sm btn-light" type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#editSubject{{ $subject->id }}">
                                <i class="bi bi-pencil me-1"></i>Edit
                            </button>
                            <form method="POST" action="{{ route('subjects.destroy', $subject) }}"
                                  onsubmit="return confirm('Delete subject? This will unlink related items.')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-light text-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>

                        {{-- Inline edit --}}
                        <div class="collapse mt-3" id="editSubject{{ $subject->id }}">
                            <form method="POST" action="{{ route('subjects.update', $subject) }}">
                                @csrf @method('PUT')
                                <div class="mb-2">
                                    <input type="text" name="name" class="form-control form-control-sm"
                                           value="{{ $subject->name }}" required>
                                </div>
                                <div class="mb-2 d-flex gap-2 align-items-center">
                                    <input type="color" name="color"
                                           class="form-control form-control-color form-control-sm"
                                           value="{{ $subject->color }}"
                                           style="width:40px;height:32px">
                                    <input type="text" name="description"
                                           class="form-control form-control-sm"
                                           value="{{ $subject->description }}"
                                           placeholder="Description">
                                </div>
                                <button class="btn btn-sm btn-primary w-100">
                                    Save Changes
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5 text-muted">
                        <i class="bi bi-book fs-1 d-block mb-3 text-secondary"></i>
                        <h5>No subjects yet</h5>
                        <p class="small">Create subjects to organize your tasks, notes, and flashcards.</p>
                    </div>
                </div>
            </div>
            @endforelse
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
// Sync color picker → hex text display
document.querySelector('input[type="color"][name="color"]')
    ?.addEventListener('input', function() {
        document.getElementById('colorHex').value = this.value;
    });
</script>
@endpush
