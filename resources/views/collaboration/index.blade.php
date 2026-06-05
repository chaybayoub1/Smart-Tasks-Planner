{{-- resources/views/collaboration/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Study Groups')
@section('page-title', 'Study Groups')

@push('styles')
<style>
    .collab-card {
        background: rgba(255,255,255,.04);
        border: 1px solid rgba(255,255,255,.08);
        border-radius: 18px; padding: 1.4rem;
        backdrop-filter: blur(10px);
        transition: transform .2s, box-shadow .2s, border-color .2s;
    }
    .collab-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 32px rgba(0,0,0,.25);
        border-color: rgba(99,102,241,.25); 
    }
    .collab-card-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: .85rem; }
    .group-icon {
        width: 44px; height: 44px; border-radius: 12px; flex-shrink: 0;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; color: #fff;
        box-shadow: 0 4px 14px rgba(99,102,241,.3);
    }
    .group-name {
        font-family: 'Syne', sans-serif;
        font-size: 1rem; font-weight: 700; color: #f1f5f9;
        text-decoration: none; letter-spacing: -.01em;
    }
    .group-name:hover { color: #a5b4fc; }
    .group-desc { font-size: .82rem; color: rgba(255,255,255,.45); line-height: 1.6; margin-bottom: .75rem; }
    .group-meta { display: flex; gap: .5rem; flex-wrap: wrap; }
    .gmeta-tag {
        display: inline-flex; align-items: center; gap: .3rem;
        padding: .18rem .55rem; border-radius: 999px; font-size: .7rem; font-weight: 600;
        background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.1); color: rgba(255,255,255,.45);
    }
    .gmeta-tag.indigo { background: rgba(99,102,241,.12); border-color: rgba(99,102,241,.25); color: #a5b4fc; }

    .member-avatars { display: flex; }
    .member-av {
        width: 26px; height: 26px; border-radius: 50%;
        background: linear-gradient(135deg, #6366f1, #818cf8);
        border: 2px solid #0f0e1f;
        display: flex; align-items: center; justify-content: center;
        font-size: .65rem; font-weight: 700; color: #fff;
        margin-left: -6px; flex-shrink: 0;
    }
    .member-av:first-child { margin-left: 0; }

    .pro-btn-indigo {
        display: inline-flex; align-items: center; gap: .45rem;
        padding: .55rem 1.1rem;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        border: none; border-radius: 10px;
        color: #fff; font-size: .82rem; font-weight: 700;
        font-family: 'Syne', sans-serif; cursor: pointer;
        transition: transform .15s, filter .15s;
        box-shadow: 0 4px 14px rgba(99,102,241,.35);
        text-decoration: none;
    }
    .pro-btn-indigo:hover { filter: brightness(1.1); transform: translateY(-1px); color: #fff; }

    .pro-btn-ghost2 {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .5rem .95rem;
        background: rgba(255,255,255,.06);
        border: 1px solid rgba(255,255,255,.12);
        border-radius: 10px;
        color: rgba(255,255,255,.6); font-size: .82rem; font-weight: 500;
        cursor: pointer; transition: all .15s; text-decoration: none;
    }
    .pro-btn-ghost2:hover { background: rgba(255,255,255,.1); color: #fff; }

    .modal-dark .modal-content {
        background: #131128;
        border: 1px solid rgba(255,255,255,.1);
        border-radius: 18px; color: #e2e8f0;
    }
    .modal-dark .modal-header { border-color: rgba(255,255,255,.08); }
    .modal-dark .modal-footer { border-color: rgba(255,255,255,.08); }
    .modal-dark .btn-close { filter: invert(1); }

    .pro-input-m {
        width: 100%; padding: .65rem .9rem .65rem 2.55rem;
        background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.1);
        border-radius: 10px; color: #e2e8f0; font-size: .875rem;
        font-family: 'DM Sans', sans-serif; outline: none;
        transition: border-color .2s, background .2s, box-shadow .2s;
    }
    .pro-input-m::placeholder { color: rgba(255,255,255,.22); }
    .pro-input-m:focus { border-color: rgba(99,102,241,.65); background: rgba(99,102,241,.08); box-shadow: 0 0 0 3px rgba(99,102,241,.14); }
    .pro-input-m.no-icon { padding-left: .9rem; }

    .pro-label-m {
        display: block; font-size: .72rem; font-weight: 600;
        text-transform: uppercase; letter-spacing: .07em;
        color: rgba(255,255,255,.4); margin-bottom: .35rem;
    }
    .input-mwrap { position: relative; }
    .input-mwrap .mi { position: absolute; left: .9rem; top: 50%; transform: translateY(-50%); color: rgba(255,255,255,.3); font-size: .9rem; pointer-events: none; }
    .pro-fgroup { margin-bottom: .85rem; }
</style>
@endpush

@section('content')

{{-- ── HEADER ──────────────────────────────────────────────────── --}}
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem;margin-bottom:1.5rem">
    <div>
        <div style="font-family:'Syne',sans-serif;font-size:1.5rem;font-weight:800;color:#f1f5f9;letter-spacing:-.02em;margin-bottom:.2rem">
            Study Groups
        </div>
        <p style="color:rgba(255,255,255,.4);font-size:.82rem;margin:0">Collaborate, plan, and study together.</p>
    </div>
    <div style="display:flex;gap:.5rem">
        <button class="pro-btn-ghost2" data-bs-toggle="modal" data-bs-target="#joinModal">
            <i class="bi bi-door-open"></i> Join Group
        </button>
        <button class="pro-btn-indigo" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="bi bi-plus-lg"></i> Create Group
        </button>
    </div>
</div>

{{-- ── GROUP CARDS GRID ────────────────────────────────────────── --}}
@if($myGroups->isEmpty())
<div style="text-align:center;padding:4rem 1rem">
    <div style="width:64px;height:64px;border-radius:16px;background:rgba(99,102,241,.1);border:1px solid rgba(99,102,241,.2);display:flex;align-items:center;justify-content:center;font-size:1.8rem;margin:0 auto 1.25rem">
        <i class="bi bi-people" style="color:#818cf8"></i>
    </div>
    <div style="font-family:'Syne',sans-serif;font-size:1.1rem;font-weight:700;color:#f1f5f9;margin-bottom:.5rem">No groups yet</div>
    <p style="color:rgba(255,255,255,.4);font-size:.85rem;margin-bottom:1.25rem">Create a group or join one with an invite code.</p>
    <button class="pro-btn-indigo" data-bs-toggle="modal" data-bs-target="#createModal">
        <i class="bi bi-plus-lg"></i> Create your first group
    </button>
</div>
@else
<div class="row g-3">
    @foreach($myGroups as $group)
    <div class="col-md-4 col-sm-6">
        <div class="collab-card h-100">
            <div class="collab-card-header">
                <div style="display:flex;align-items:center;gap:.85rem;flex:1;min-width:0">
                    <div class="group-icon"><i class="bi bi-people-fill"></i></div>
                    <div style="min-width:0">
                        <a href="{{ route('collaboration.show', $group) }}" class="group-name d-block" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                            {{ $group->name }}
                        </a>
                        @if($group->subject)
                            <span style="font-size:.72rem;color:rgba(255,255,255,.35)">{{ $group->subject }}</span>
                        @endif
                    </div>
                </div>
                @if($group->owner_id === auth()->id())
                    <span class="gmeta-tag indigo" style="flex-shrink:0;margin-left:.5rem">Owner</span>
                @endif
            </div>

            @if($group->description)
                <div class="group-desc">{{ Str::limit($group->description, 90) }}</div>
            @endif

            <div style="display:flex;align-items:center;justify-content:space-between;margin-top:auto">
                {{-- Member avatars --}}
                <div style="display:flex;align-items:center;gap:.5rem">
                    <div class="member-avatars">
                        @foreach($group->members->take(4) as $member)
                            <div class="member-av" title="{{ $member->name }}">{{ $member->initials() }}</div>
                        @endforeach
                        @if($group->members->count() > 4)
                            <div class="member-av" style="background:rgba(255,255,255,.1);color:rgba(255,255,255,.5);font-size:.6rem">
                                +{{ $group->members->count() - 4 }}
                            </div>
                        @endif
                    </div>
                    <span style="font-size:.72rem;color:rgba(255,255,255,.35)">{{ $group->members->count() }} / {{ $group->max_members }}</span>
                </div>

                <a href="{{ route('collaboration.show', $group) }}" class="pro-btn-ghost2" style="font-size:.75rem;padding:.35rem .7rem">
                    Open <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- ── CREATE GROUP MODAL ──────────────────────────────────────── --}}
<div class="modal fade modal-dark" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 style="font-family:'Syne',sans-serif;font-weight:700;color:#f1f5f9;margin:0">Create Study Group</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('collaboration.store') }}">
                @csrf
                <div class="modal-body" style="padding:1.25rem">
                    <div class="pro-fgroup">
                        <label class="pro-label-m">Group Name *</label>
                        <div class="input-mwrap">
                            <i class="bi bi-people-fill mi"></i>
                            <input type="text" name="name" class="pro-input-m" placeholder="e.g. Calculus Study Crew" required>
                        </div>
                    </div>
                    <div class="pro-fgroup">
                        <label class="pro-label-m">Subject / Topic</label>
                        <div class="input-mwrap">
                            <i class="bi bi-journal-bookmark mi"></i>
                            <input type="text" name="subject" class="pro-input-m" placeholder="e.g. Mathematics, Physics…">
                        </div>
                    </div>
                    <div class="pro-fgroup">
                        <label class="pro-label-m">Description</label>
                        <textarea name="description" class="pro-input-m no-icon" style="padding-left:.9rem;resize:vertical;min-height:72px" placeholder="What will your group study?"></textarea>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem">
                        <div class="pro-fgroup" style="margin-bottom:0">
                            <label class="pro-label-m">Max Members</label>
                            <div class="input-mwrap">
                                <i class="bi bi-person-plus mi"></i>
                                <input type="number" name="max_members" class="pro-input-m" value="10" min="2" max="50">
                            </div>
                        </div>
                        <div class="pro-fgroup" style="margin-bottom:0;display:flex;align-items:flex-end">
                            <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer;padding-bottom:.2rem">
                                <input type="checkbox" name="is_public" value="1" style="accent-color:#6366f1;width:16px;height:16px">
                                <span style="font-size:.82rem;color:rgba(255,255,255,.5)">Public group</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="pro-btn-ghost2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="pro-btn-indigo">
                        <i class="bi bi-rocket-takeoff-fill"></i> Create Group
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ── JOIN GROUP MODAL ────────────────────────────────────────── --}}
<div class="modal fade modal-dark" id="joinModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 style="font-family:'Syne',sans-serif;font-weight:700;color:#f1f5f9;margin:0">Join a Group</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('collaboration.join') }}">
                @csrf
                <div class="modal-body" style="padding:1.25rem">
                    <div class="pro-fgroup" style="margin-bottom:0">
                        <label class="pro-label-m">6-Character Invite Code</label>
                        <div class="input-mwrap">
                            <i class="bi bi-key-fill mi"></i>
                            <input type="text" name="invite_code" class="pro-input-m"
                                   placeholder="ABC123" maxlength="6"
                                   style="text-transform:uppercase;letter-spacing:.15em;font-weight:700;font-size:1rem"
                                   required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="pro-btn-ghost2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="pro-btn-indigo">
                        <i class="bi bi-door-open"></i> Join
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
