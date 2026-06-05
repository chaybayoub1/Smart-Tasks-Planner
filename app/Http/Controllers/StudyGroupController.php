<?php
// app/Http/Controllers/StudyGroupController.php

namespace App\Http\Controllers;

use App\Models\StudyGroup;
use App\Models\GroupMessage;
use App\Models\GroupTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudyGroupController extends Controller
{
    // List all groups the user belongs to
    public function index()
    {
        $user   = Auth::user();
        $myGroups = $user->studyGroups()->with('owner', 'members')->get();
        return view('collaboration.index', compact('myGroups'));
    }

    // Create a new group
    public function store(Request $request)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:300'],
            'subject'     => ['nullable', 'string', 'max:100'],
            'is_public'   => ['boolean'],
            'max_members' => ['integer', 'min:2', 'max:50'],
        ]);

        $group = StudyGroup::create([
            'name'        => $request->name,
            'description' => $request->description,
            'subject'     => $request->subject,
            'owner_id'    => Auth::id(),
            'is_public'   => $request->boolean('is_public'),
            'max_members' => $request->max_members ?? 10,
        ]);

        // Owner auto-joins as owner
        $group->members()->attach(Auth::id(), ['role' => 'owner', 'joined_at' => now()]);

        return redirect()->route('collaboration.show', $group)
            ->with('success', 'Study group created!');
    }

    // Show group detail: members, chat, tasks
    public function show(StudyGroup $studyGroup)
    {
        $this->authorize_member($studyGroup);

        $studyGroup->load([
            'owner',
            'members',
            'messages' => fn($q) => $q->with('user')->latest()->take(50),
            'groupTasks' => fn($q) => $q->with('creator', 'assignee')->orderBy('due_date'),
        ]);

        $messages = $studyGroup->messages->reverse()->values();
        $tasks    = $studyGroup->groupTasks;
        $members  = $studyGroup->members;
        $userRole = $studyGroup->members()->where('user_id', Auth::id())->first()?->pivot->role ?? 'member';

        return view('collaboration.show', compact('studyGroup', 'messages', 'tasks', 'members', 'userRole'));
    }

    // Join via invite code
    public function join(Request $request)
    {
        $request->validate(['invite_code' => ['required', 'string', 'size:6']]);

        $group = StudyGroup::where('invite_code', strtoupper($request->invite_code))->firstOrFail();

        if ($group->isMember(Auth::user())) {
            return redirect()->route('collaboration.show', $group)->with('status', 'already-member');
        }

        if ($group->memberCount() >= $group->max_members) {
            return back()->with('error', 'This group is full.');
        }

        $group->members()->attach(Auth::id(), ['role' => 'member', 'joined_at' => now()]);

        return redirect()->route('collaboration.show', $group)->with('success', 'You joined the group!');
    }

    // Leave group
    public function leave(StudyGroup $studyGroup)
    {
        if ($studyGroup->owner_id === Auth::id()) {
            return back()->with('error', 'Transfer ownership before leaving.');
        }
        $studyGroup->members()->detach(Auth::id());
        return redirect()->route('collaboration.index')->with('success', 'You left the group.');
    }

    // Delete group (owner only)
    public function destroy(StudyGroup $studyGroup)
    {
        abort_unless($studyGroup->owner_id === Auth::id(), 403);
        $studyGroup->delete();
        return redirect()->route('collaboration.index')->with('success', 'Group deleted.');
    }

    // Post a chat message
    public function sendMessage(Request $request, StudyGroup $studyGroup)
    {
        $this->authorize_member($studyGroup);
        $request->validate(['message' => ['required', 'string', 'max:1000']]);

        GroupMessage::create([
            'study_group_id' => $studyGroup->id,
            'user_id'        => Auth::id(),
            'message'        => $request->message,
        ]);

        return back();
    }

    // Add a group task
    public function storeTask(Request $request, StudyGroup $studyGroup)
    {
        $this->authorize_member($studyGroup);
        $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date'    => ['nullable', 'date'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'priority'    => ['in:low,medium,high'],
        ]);

        GroupTask::create([
            'study_group_id' => $studyGroup->id,
            'created_by'     => Auth::id(),
            'assigned_to'    => $request->assigned_to,
            'title'          => $request->title,
            'description'    => $request->description,
            'due_date'       => $request->due_date,
            'priority'       => $request->priority ?? 'medium',
        ]);

        return back()->with('success', 'Task added!');
    }

    // Toggle task status
    public function toggleTask(StudyGroup $studyGroup, GroupTask $groupTask)
    {
        $this->authorize_member($studyGroup);
        $groupTask->update([
            'status' => $groupTask->status === 'completed' ? 'pending' : 'completed',
        ]);
        return back();
    }

    // Regenerate invite code (owner only)
    public function regenerateCode(StudyGroup $studyGroup)
    {
        abort_unless($studyGroup->owner_id === Auth::id(), 403);
        do {
            $code = strtoupper(\Illuminate\Support\Str::random(6));
        } while (StudyGroup::where('invite_code', $code)->exists());

        $studyGroup->update(['invite_code' => $code]);
        return back()->with('success', 'Invite code regenerated.');
    }

    // Helper
    private function authorize_member(StudyGroup $group): void
    {
        abort_unless($group->isMember(Auth::user()), 403, 'You are not a member of this group.');
    }
}
