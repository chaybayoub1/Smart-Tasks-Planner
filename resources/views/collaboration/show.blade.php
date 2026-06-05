{{-- resources/views/collaboration/show.blade.php --}}
@extends('layouts.app')
@section('title', $studyGroup->name)
@section('page-title', $studyGroup->name)

@push('styles')
<style>
    :root { --indigo: #6366f1; --indigo-d: #4f46e5; }

    .grp-layout { display: grid; grid-template-columns: 1fr 320px; gap: 1.25rem; align-items: start; }
    @media (max-width: 900px) { .grp-layout { grid-template-columns: 1fr; } }

    .grp-card {
        background: rgba(255,255,255,.04);
        border: 1px solid rgba(255,255,255,.08);
        border-radius: 18px; overflow: hidden;
    }
    .grp-card-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: .9rem 1.2rem;
        border-bottom: 1px solid rgba(255,255,255,.07);
    }
    .grp-card-title {
        font-family: 'Syne', sans-serif; font-size: .85rem; font-weight: 700;
        color: #f1f5f9; display: flex; align-items: center; gap: .5rem;
    }
    .grp-card-title .ticon {
        width: 26px; height: 26px; border-radius: 7px;
        display: flex; align-items: center; justify-content: center; font-size: .78rem;
    }
    .ti-indigo { background: rgba(99,102,241,.15); color: #818cf8; }
    .ti-green  { background: rgba(34,197,94,.12);  color: #86efac; }
    .ti-amber  { background: rgba(245,158,11,.12); color: #fcd34d; }

    /* ── CHAT ──────────────────────────────────────────────── */
    .chat-area {
        height: 420px; overflow-y: auto;
        display: flex; flex-direction: column; gap: .6rem;
        padding: 1rem;
    }
    .chat-area::-webkit-scrollbar { width: 4px; }
    .chat-area::-webkit-scrollbar-thumb { background: rgba(255,255,255,.1); border-radius: 99px; }

    .chat-msg { display: flex; gap: .6rem; align-items: flex-start; }
    .chat-msg.mine { flex-direction: row-reverse; }
    .chat-av {
        width: 30px; height: 30px; border-radius: 50%; flex-shrink: 0;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        display: flex; align-items: center; justify-content: center;
        font-size: .68rem; font-weight: 700; color: #fff;
        overflow: hidden;
    }
    .chat-av img { width: 100%; height: 100%; object-fit: cover; }
    .chat-bubble {
        max-width: 72%; padding: .55rem .8rem;
        border-radius: 14px; font-size: .83rem; line-height: 1.55;
        color: #e2e8f0; word-break: break-word;
        background: rgba(255,255,255,.07);
        border: 1px solid rgba(255,255,255,.07);
    }
    .chat-msg.mine .chat-bubble {
        background: rgba(99,102,241,.2);
        border-color: rgba(99,102,241,.3);
    }
    .chat-meta {
        font-size: .65rem; color: rgba(255,255,255,.3);
        margin-top: .2rem; white-space: nowrap;
    }
    .chat-msg.mine .chat-meta { text-align: right; }

    .chat-input-row {
        display: flex; gap: .5rem; padding: .75rem 1rem;
        border-top: 1px solid rgba(255,255,255,.07);
    }
    .chat-input {
        flex: 1; padding: .6rem .9rem;
        background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.1);
        border-radius: 10px; color: #e2e8f0; font-size: .875rem;
        font-family: 'DM Sans', sans-serif; outline: none;
        transition: border-color .2s, box-shadow .2s;
    }
    .chat-input::placeholder { color: rgba(255,255,255,.22); }
    .chat-input:focus { border-color: rgba(99,102,241,.5); box-shadow: 0 0 0 3px rgba(99,102,241,.1); }
    .chat-send-btn {
        padding: .6rem .9rem;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        border: none; border-radius: 10px; color: #fff; cursor: pointer;
        font-size: .95rem; transition: filter .15s;
    }
    .chat-send-btn:hover { filter: brightness(1.1); }

    /* ── TASKS ─────────────────────────────────────────────── */
    .task-row-g {
        display: flex; align-items: center; gap: .75rem;
        padding: .7rem 1rem;
        border-bottom: 1px solid rgba(255,255,255,.05);
        transition: background .12s;
    }
    .task-row-g:last-child { border-bottom: none; }
    .task-row-g:hover { background: rgba(255,255,255,.025); }

    .task-check-g {
        background: none; border: none; cursor: pointer; padding: 0;
        font-size: 1.15rem; color: rgba(255,255,255,.3);
        transition: color .15s; line-height: 1; flex-shrink: 0;
    }
    .task-check-g.done { color: #86efac; }

    .ptag {
        display: inline-flex; padding: .15rem .5rem; border-radius: 999px;
        font-size: .67rem; font-weight: 600;
    }
    .ptag-high   { background: rgba(239,68,68,.12); color: #fca5a5; border: 1px solid rgba(239,68,68,.2); }
    .ptag-medium { background: rgba(245,158,11,.12); color: #fcd34d; border: 1px solid rgba(245,158,11,.2); }
    .ptag-low    { background: rgba(34,197,94,.1);  color: #86efac; border: 1px solid rgba(34,197,94,.2); }

    /* ── MEMBERS ───────────────────────────────────────────── */
    .member-row {
        display: flex; align-items: center; gap: .75rem;
        padding: .65rem 1rem;
        border-bottom: 1px solid rgba(255,255,255,.05);
    }
    .member-row:last-child { border-bottom: none; }
    .mav {
        width: 36px; height: 36px; border-radius: 50%; flex-shrink: 0;
        background: linear-gradient(135deg, #6366f1, #818cf8);
        display: flex; align-items: center; justify-content: center;
        font-size: .8rem; font-weight: 700; color: #fff;
        overflow: hidden;
    }
    .mav img { width: 100%; height: 100%; object-fit: cover; }

    /* ── INVITE CODE BOX ───────────────────────────────────── */
    .invite-box {
        background: rgba(99,102,241,.08);
        border: 1px solid rgba(99,102,241,.2);
        border-radius: 12px; padding: .85rem 1rem;
        display: flex; align-items: center; justify-content: space-between; gap: .5rem;
    }
    .invite-code-display {
        font-family: 'Syne', sans-serif;
        font-size: 1.4rem; font-weight: 800;
        color: #a5b4fc; letter-spacing: .2em;
    }

    .btn-sm-indigo {
        display: inline-flex; align-items: center; gap: .3rem;
        padding: .35rem .7rem;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        border: none; border-radius: 8px; color: #fff;
        font-size: .75rem; font-weight: 600; cursor: pointer;
        transition: filter .15s; text-decoration: none;
    }
    .btn-sm-indigo:hover { filter: brightness(1.1); color: #fff; }
    .btn-sm-ghost {
        display: inline-flex; align-items: center; gap: .3rem;
        padding: .35rem .7rem;
        background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.1);
        border-radius: 8px; color: rgba(255,255,255,.55);
        font-size: .75rem; cursor: pointer; transition: all .15s; text-decoration: none;
    }
    .btn-sm-ghost:hover { background: rgba(255,255,255,.1); color: #fff; }
    .btn-sm-danger {
        display: inline-flex; align-items: center; gap: .3rem;
        padding: .35rem .7rem;
        background: rgba(239,68,68,.1); border: 1px solid rgba(239,68,68,.2);
        border-radius: 8px; color: #fca5a5;
        font-size: .75rem; cursor: pointer; transition: all .15s;
    }
    .btn-sm-danger:hover { background: rgba(239,68,68,.18); }

    .pro-input-sm {
        width: 100%; padding: .55rem .85rem .55rem 2.4rem;
        background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.1);
        border-radius: 9px; color: #e2e8f0; font-size: .83rem;
        font-family: 'DM Sans', sans-serif; outline: none;
        transition: border-color .2s, background .2s;
    }
    .pro-input-sm::placeholder { color: rgba(255,255,255,.22); }
    .pro-input-sm:focus { border-color: rgba(99,102,241,.55); background: rgba(99,102,241,.07); }
    .pro-input-sm.no-icon { padding-left: .85rem; }
    .pro-label-sm {
        display: block; font-size: .68rem; font-weight: 600;
        text-transform: uppercase; letter-spacing: .07em;
        color: rgba(255,255,255,.38); margin-bottom: .3rem;
    }
    .pro-fgroup-sm { margin-bottom: .7rem; }
    .iwrap { position: relative; }
    .iwrap .ii { position: absolute; left: .8rem; top: 50%; transform: translateY(-50%); color: rgba(255,255,255,.3); font-size: .85rem; pointer-events: none; }

    /* Progress bars for member stats */
    .stat-mini { font-size: .72rem; color: rgba(255,255,255,.35); }
    .mini-bar { height: 3px; background: rgba(255,255,255,.08); border-radius: 99px; overflow: hidden; margin-top: 2px; }
    .mini-fill { height: 100%; background: linear-gradient(90deg, #6366f1, #818cf8); border-radius: 99px; }
</style>
@endpush

@section('content')

{{-- Breadcrumb --}}
<div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1.25rem;font-size:.82rem;color:rgba(255,255,255,.35)">
    <a href="{{ route('collaboration.index') }}" style="color:rgba(255,255,255,.4);text-decoration:none">
        <i class="bi bi-people me-1"></i>Groups
    </a>
    <i class="bi bi-chevron-right" style="font-size:.7rem"></i>
    <span style="color:#a5b4fc">{{ $studyGroup->name }}</span>
</div>

<div class="grp-layout">

    {{-- ── LEFT: Chat + Tasks ──────────────────────────────────── --}}
    <div style="display:flex;flex-direction:column;gap:1.25rem">

        {{-- Group Chat --}}
        <div class="grp-card">
            <div class="grp-card-header">
                <div class="grp-card-title">
                    <div class="ticon ti-indigo"><i class="bi bi-chat-dots-fill"></i></div>
                    Group Chat
                </div>
                <span style="font-size:.72rem;color:rgba(255,255,255,.3)">{{ $members->count() }} members online</span>
            </div>

            <div class="chat-area" id="chatArea">
                @forelse($messages as $msg)
                <div class="chat-msg {{ $msg->user_id === auth()->id() ? 'mine' : '' }}">
                    <div class="chat-av">
                        @if($msg->user->avatar)
                            <img src="{{ $msg->user->avatarUrl() }}" alt="">
                        @else
                            {{ $msg->user->initials() }}
                        @endif
                    </div>
                    <div>
                        <div class="chat-bubble">{{ $msg->message }}</div>
                        <div class="chat-meta">
                            {{ $msg->user_id === auth()->id() ? 'You' : $msg->user->name }}
                            &middot; {{ $msg->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
                @empty
                <div style="text-align:center;padding:2rem;color:rgba(255,255,255,.3);font-size:.85rem">
                    No messages yet. Say hello!
                </div>
                @endforelse
            </div>

            <form method="POST" action="{{ route('collaboration.message', $studyGroup) }}">
                @csrf
                <div class="chat-input-row">
                    <input type="text" name="message" class="chat-input"
                           placeholder="Type a message…" required autocomplete="off">
                    <button type="submit" class="chat-send-btn">
                        <i class="bi bi-send-fill"></i>
                    </button>
                </div>
            </form>
        </div>

        {{-- Group Tasks --}}
        <div class="grp-card">
            <div class="grp-card-header">
                <div class="grp-card-title">
                    <div class="ticon ti-green"><i class="bi bi-check2-square"></i></div>
                    Shared Tasks
                </div>
                <button class="btn-sm-indigo" type="button" data-bs-toggle="collapse" data-bs-target="#addTaskForm">
                    <i class="bi bi-plus"></i> Add Task
                </button>
            </div>

            {{-- Add task form --}}
            <div class="collapse" id="addTaskForm">
                <form method="POST" action="{{ route('collaboration.tasks.store', $studyGroup) }}"
                      style="padding:1rem;border-bottom:1px solid rgba(255,255,255,.06)">
                    @csrf
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:.65rem">
                        <div class="pro-fgroup-sm" style="grid-column:span 2">
                            <label class="pro-label-sm">Task Title *</label>
                            <div class="iwrap">
                                <i class="bi bi-pencil-fill ii"></i>
                                <input type="text" name="title" class="pro-input-sm" placeholder="e.g. Review Chapter 4" required>
                            </div>
                        </div>
                        <div class="pro-fgroup-sm">
                            <label class="pro-label-sm">Due Date</label>
                            <input type="date" name="due_date" class="pro-input-sm no-icon">
                        </div>
                        <div class="pro-fgroup-sm">
                            <label class="pro-label-sm">Assign To</label>
                            <select name="assigned_to" class="pro-input-sm no-icon" style="appearance:none;cursor:pointer">
                                <option value="">— Anyone —</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}">{{ $member->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="pro-fgroup-sm">
                            <label class="pro-label-sm">Priority</label>
                            <select name="priority" class="pro-input-sm no-icon" style="appearance:none;cursor:pointer">
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="low">Low</option>
                            </select>
                        </div>
                        <div class="pro-fgroup-sm d-flex align-items-end">
                            <button type="submit" class="btn-sm-indigo" style="width:100%;justify-content:center;padding:.55rem">
                                <i class="bi bi-plus-circle-fill"></i> Add Task
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Task list --}}
            @forelse($tasks as $task)
            <div class="task-row-g">
                <form method="POST" action="{{ route('collaboration.tasks.toggle', [$studyGroup, $task]) }}" style="flex-shrink:0">
                    @csrf @method('PATCH')
                    <button class="task-check-g {{ $task->status === 'completed' ? 'done' : '' }}">
                        <i class="bi bi-{{ $task->status === 'completed' ? 'check-circle-fill' : 'circle' }}"></i>
                    </button>
                </form>
                <div style="flex:1;min-width:0">
                    <div style="font-size:.87rem;font-weight:500;color:{{ $task->status === 'completed' ? 'rgba(255,255,255,.3)' : '#e2e8f0' }};{{ $task->status === 'completed' ? 'text-decoration:line-through' : '' }};overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                        {{ $task->title }}
                    </div>
                    <div style="display:flex;align-items:center;gap:.4rem;margin-top:.2rem;flex-wrap:wrap">
                        @if($task->assignee)
                            <span style="font-size:.7rem;color:rgba(255,255,255,.35)">
                                <i class="bi bi-person-fill me-1"></i>{{ $task->assignee->name }}
                            </span>
                        @endif
                        @if($task->due_date)
                            <span style="font-size:.7rem;color:{{ $task->isOverdue() ? '#fca5a5' : 'rgba(255,255,255,.35)' }}">
                                <i class="bi bi-calendar3 me-1"></i>{{ $task->due_date->format('M d') }}
                            </span>
                        @endif
                    </div>
                </div>
                <span class="ptag ptag-{{ $task->priority }}">{{ $task->priority }}</span>
            </div>
            @empty
            <div style="text-align:center;padding:1.5rem;color:rgba(255,255,255,.3);font-size:.82rem">
                No tasks yet. Add one to get started!
            </div>
            @endforelse
        </div>
    </div>

    {{-- ── RIGHT: Members + Info ───────────────────────────────── --}}
    <div style="display:flex;flex-direction:column;gap:1.25rem">

        {{-- Invite Code --}}
        @if($userRole === 'owner' || $userRole === 'admin')
        <div class="grp-card">
            <div class="grp-card-header">
                <div class="grp-card-title">
                    <div class="ticon ti-amber"><i class="bi bi-key-fill"></i></div>
                    Invite Code
                </div>
            </div>
            <div style="padding:1rem">
                <div class="invite-box">
                    <div>
                        <div style="font-size:.68rem;color:rgba(255,255,255,.35);margin-bottom:.25rem;text-transform:uppercase;letter-spacing:.08em">Share this code</div>
                        <div class="invite-code-display" id="inviteCode">{{ $studyGroup->invite_code }}</div>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:.35rem">
                        <button onclick="copyCode()" class="btn-sm-ghost">
                            <i class="bi bi-clipboard"></i> Copy
                        </button>
                        <form method="POST" action="{{ route('collaboration.regenerate-code', $studyGroup) }}">
                            @csrf
                            <button type="submit" class="btn-sm-ghost" style="width:100%;justify-content:center">
                                <i class="bi bi-arrow-repeat"></i> New
                            </button>
                        </form>
                    </div>
                </div>
                <p style="font-size:.72rem;color:rgba(255,255,255,.3);margin-top:.65rem;margin-bottom:0">
                    Share this code with anyone you want to invite. They go to Study Groups → Join Group.
                </p>
            </div>
        </div>
        @endif

        {{-- Members --}}
        <div class="grp-card">
            <div class="grp-card-header">
                <div class="grp-card-title">
                    <div class="ticon ti-indigo"><i class="bi bi-people-fill"></i></div>
                    Members
                </div>
                <span style="font-size:.72rem;color:rgba(255,255,255,.3)">{{ $members->count() }} / {{ $studyGroup->max_members }}</span>
            </div>

            @foreach($members as $member)
            <div class="member-row">
                <div class="mav">
                    @if($member->avatar)
                        <img src="{{ $member->avatarUrl() }}" alt="">
                    @else
                        {{ $member->initials() }}
                    @endif
                </div>
                <div style="flex:1;min-width:0">
                    <div style="font-size:.85rem;font-weight:600;color:#e2e8f0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                        {{ $member->name }}
                        @if($member->id === auth()->id())
                            <span style="font-size:.68rem;color:rgba(255,255,255,.3)">(you)</span>
                        @endif
                    </div>
                    @if($member->university)
                        <div class="stat-mini">{{ $member->university }}</div>
                    @endif
                    {{-- XP mini bar --}}
                    <div style="display:flex;align-items:center;gap:.4rem;margin-top:.3rem">
                        <div class="mini-bar" style="flex:1">
                            <div class="mini-fill" style="width:{{ $member->xpProgress() }}%"></div>
                        </div>
                        <span class="stat-mini">Lv.{{ $member->level }}</span>
                    </div>
                </div>
                @php $pivotRole = $member->pivot->role ?? 'member'; @endphp
                @if($pivotRole === 'owner')
                    <span style="font-size:.68rem;font-weight:700;color:#a5b4fc;background:rgba(99,102,241,.15);padding:.15rem .5rem;border-radius:999px;border:1px solid rgba(99,102,241,.25)">Owner</span>
                @endif
            </div>
            @endforeach
        </div>

        {{-- Group Info --}}
        <div class="grp-card">
            <div style="padding:1rem">
                <div style="font-family:'Syne',sans-serif;font-size:.85rem;font-weight:700;color:#f1f5f9;margin-bottom:.75rem">
                    {{ $studyGroup->name }}
                </div>
                @if($studyGroup->description)
                    <p style="font-size:.82rem;color:rgba(255,255,255,.45);line-height:1.6;margin-bottom:.75rem">{{ $studyGroup->description }}</p>
                @endif
                @if($studyGroup->subject)
                    <div style="font-size:.75rem;color:rgba(255,255,255,.35);margin-bottom:.4rem">
                        <i class="bi bi-journal-bookmark me-1"></i>{{ $studyGroup->subject }}
                    </div>
                @endif
                <div style="font-size:.75rem;color:rgba(255,255,255,.3)">
                    <i class="bi bi-calendar3 me-1"></i>Created {{ $studyGroup->created_at->format('M d, Y') }}
                </div>

                <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid rgba(255,255,255,.06);display:flex;gap:.5rem;flex-wrap:wrap">
                    @if($studyGroup->owner_id !== auth()->id())
                    <form method="POST" action="{{ route('collaboration.leave', $studyGroup) }}" onsubmit="return confirm('Leave this group?')">
                        @csrf
                        <button type="submit" class="btn-sm-danger"><i class="bi bi-box-arrow-left"></i> Leave Group</button>
                    </form>
                    @else
                    <form method="POST" action="{{ route('collaboration.destroy', $studyGroup) }}" onsubmit="return confirm('Delete this group permanently?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-sm-danger"><i class="bi bi-trash"></i> Delete Group</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
// Auto-scroll chat to bottom
const ca = document.getElementById('chatArea');
if (ca) ca.scrollTop = ca.scrollHeight;

// Copy invite code
function copyCode() {
    const code = document.getElementById('inviteCode')?.textContent?.trim();
    if (!code) return;
    navigator.clipboard.writeText(code).then(() => {
        const btn = event.currentTarget;
        const orig = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check-lg"></i> Copied!';
        setTimeout(() => btn.innerHTML = orig, 2000);
    });
}

// Enter to send chat
document.querySelector('.chat-input')?.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        this.closest('form').submit();
    }
});
</script>
@endpush
