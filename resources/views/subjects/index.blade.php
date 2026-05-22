{{-- resources/views/subjects/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Subjects')
@section('page-title', 'Subjects')

@section('content')
<div class="row g-4">

    {{-- ── ADD SUBJECT FORM ──────────────────────────────────── --}}
    <div class="col-12">
        <div class="st-card">
            <div class="st-card-header">
                <div class="st-card-title">
                    <div class="icon icon-teal"><i class="bi bi-plus-circle-fill"></i></div>
                    Add New Subject
                </div>
            </div>
            <div style="padding:1.25rem">
                <form method="POST" action="{{ route('subjects.store') }}">
                    @csrf
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="st-label">Subject Name *</label>
                            <input type="text" name="name" class="st-input" placeholder="e.g. Mathematics" value="{{ old('name') }}" required>
                        </div>
                        <div class="col-md-2">
                            <label class="st-label">Colour</label>
                            <div style="display:flex;align-items:center;gap:.5rem">
                                <input type="color" name="color" id="colorPicker" value="{{ old('color', '#00d4aa') }}"
                                       style="width:44px;height:40px;border:1px solid var(--c-border2);background:var(--c-surface2);border-radius:var(--radius-sm);cursor:pointer;padding:2px">
                                <input type="text" id="colorHex" class="st-input" value="{{ old('color', '#00d4aa') }}" readonly style="width:90px;font-size:.8rem;font-family:monospace">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="st-label">Description</label>
                            <input type="text" name="description" class="st-input" placeholder="Optional description" value="{{ old('description') }}">
                        </div>
                        <div class="col-md-2">
                            <button class="btn-st-primary" style="width:100%;justify-content:center">
                                <i class="bi bi-plus"></i> Add Subject
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
                <div class="st-card h-100" style="overflow:hidden;transition:transform .2s,box-shadow .2s">
                    {{-- Color bar --}}
                    <div style="height:4px;background:{{ $subject->color }}"></div>

                    <div style="padding:1.1rem">
                        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:.85rem">
                            <div>
                                <div style="font-weight:700;font-size:.95rem;color:var(--c-text);margin-bottom:.2rem">{{ $subject->name }}</div>
                                @if($subject->description)
                                    <div style="font-size:.77rem;color:var(--c-muted)">{{ $subject->description }}</div>
                                @endif
                            </div>
                            <div style="width:28px;height:28px;border-radius:8px;background:{{ $subject->color }}20;border:1.5px solid {{ $subject->color }}40;flex-shrink:0;margin-left:.5rem"></div>
                        </div>

                        {{-- Stats --}}
                        <div style="display:flex;gap:0;margin-bottom:.85rem;background:var(--c-surface2);border-radius:var(--radius-sm);overflow:hidden">
                            <div style="flex:1;text-align:center;padding:.6rem .5rem;border-right:1px solid var(--c-border)">
                                <div style="font-family:var(--font-display);font-size:1.1rem;font-weight:800;color:var(--c-teal)">{{ $subject->tasks_count }}</div>
                                <div style="font-size:.62rem;color:var(--c-muted);text-transform:uppercase;letter-spacing:.06em">Tasks</div>
                            </div>
                            <div style="flex:1;text-align:center;padding:.6rem .5rem;border-right:1px solid var(--c-border)">
                                <div style="font-family:var(--font-display);font-size:1.1rem;font-weight:800;color:var(--c-violet)">{{ $subject->notes_count }}</div>
                                <div style="font-size:.62rem;color:var(--c-muted);text-transform:uppercase;letter-spacing:.06em">Notes</div>
                            </div>
                            <div style="flex:1;text-align:center;padding:.6rem .5rem">
                                <div style="font-family:var(--font-display);font-size:1.1rem;font-weight:800;color:var(--c-amber)">{{ $subject->flashcards_count }}</div>
                                <div style="font-size:.62rem;color:var(--c-muted);text-transform:uppercase;letter-spacing:.06em">Cards</div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div style="display:flex;gap:.4rem;justify-content:flex-end">
                            <button type="button" class="btn-st-ghost" style="font-size:.78rem;padding:.35rem .7rem"
                                    data-bs-toggle="collapse" data-bs-target="#editSubject{{ $subject->id }}">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                            <form method="POST" action="{{ route('subjects.destroy', $subject) }}" onsubmit="return confirm('Delete subject? This will unlink related items.')">
                                @csrf @method('DELETE')
                                <button class="btn-st-danger" style="font-size:.78rem;padding:.35rem .7rem">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>

                        {{-- Inline edit --}}
                        <div class="collapse" id="editSubject{{ $subject->id }}" style="margin-top:.75rem;padding-top:.75rem;border-top:1px solid var(--c-border)">
                            <form method="POST" action="{{ route('subjects.update', $subject) }}">
                                @csrf @method('PUT')
                                <div style="margin-bottom:.6rem">
                                    <input type="text" name="name" class="st-input" value="{{ $subject->name }}" required>
                                </div>
                                <div style="display:flex;gap:.5rem;align-items:center;margin-bottom:.6rem">
                                    <input type="color" name="color" class="edit-color-picker"
                                           value="{{ $subject->color }}"
                                           style="width:40px;height:36px;border:1px solid var(--c-border2);background:var(--c-surface2);border-radius:var(--radius-sm);cursor:pointer;padding:2px">
                                    <input type="text" name="description" class="st-input" value="{{ $subject->description }}" placeholder="Description">
                                </div>
                                <button class="btn-st-primary" style="width:100%;justify-content:center">Save Changes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="st-card">
                    <div class="empty-state">
                        <div class="empty-state-icon"><i class="bi bi-book"></i></div>
                        <p>No subjects yet.<br>Create subjects to organize your tasks, notes, and flashcards.</p>
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
document.getElementById('colorPicker')?.addEventListener('input', function() {
    document.getElementById('colorHex').value = this.value;
});
</script>
@endpush
