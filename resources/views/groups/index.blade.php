{{-- resources/views/groups/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Collaboration')
@section('page-title', 'Collaboration')

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center">
                <i class="bi bi-people-fill text-primary me-2"></i>
                <span>Create Group</span>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('groups.store') }}" class="d-flex flex-column gap-3">
                    @csrf
                    <div>
                        <label class="form-label fw-500 small">Group name *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="e.g. Laravel Project Team" required>
                    </div>
                    <div>
                        <label class="form-label fw-500 small">Description</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="What will this team work on?">{{ old('description') }}</textarea>
                    </div>
                    <button class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> Create Group
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        @if($invitations->isNotEmpty())
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center">
                    <i class="bi bi-envelope-paper-fill text-warning me-2"></i>
                    <span>Pending Invitations</span>
                </div>
                <div class="card-body d-flex flex-column gap-2">
                    @foreach($invitations as $invitation)
                        <div class="d-flex align-items-center justify-content-between gap-3 border rounded-3 p-3">
                            <div>
                                <div class="fw-semibold">{{ $invitation->group->name }}</div>
                                <div class="small text-muted">Invited by {{ $invitation->inviter->name }}</div>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('groups.invitations.respond', $invitation->token) }}" class="btn btn-sm btn-success">
                                    Accept
                                </a>
                                <form method="POST" action="{{ route('groups.invitations.decline', $invitation->token) }}">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-secondary">Decline</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="card">
            <div class="card-header d-flex align-items-center">
                <i class="bi bi-collection-fill text-primary me-2"></i>
                <span>Your Groups</span>
            </div>
            <div class="card-body">
                @forelse($groups as $group)
                    <a href="{{ route('groups.show', $group) }}" class="d-flex align-items-center justify-content-between gap-3 p-3 border rounded-3 text-decoration-none text-reset mb-3">
                        <div>
                            <div class="fw-bold">{{ $group->name }}</div>
                            <div class="small text-muted">{{ $group->description ?: 'No description yet.' }}</div>
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-wrap justify-content-end">
                            <span class="badge text-bg-light">
                                <i class="bi bi-person-fill"></i> {{ $group->members_count }} members
                            </span>
                            <span class="badge text-bg-primary">
                                <i class="bi bi-check2-square"></i> {{ $group->tasks_count }} tasks
                            </span>
                            <i class="bi bi-chevron-right text-muted"></i>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-people fs-1 d-block mb-3"></i>
                        No collaboration groups yet.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
