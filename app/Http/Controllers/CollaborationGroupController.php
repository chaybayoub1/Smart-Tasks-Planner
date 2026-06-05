<?php

namespace App\Http\Controllers;

use App\Models\CollaborationGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CollaborationGroupController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $groups = $user->collaborationGroups()
            ->withCount(['members', 'tasks'])
            ->orderByDesc('collaboration_groups.created_at')
            ->get();

        $invitations = $user->email
            ? \App\Models\CollaborationGroupInvitation::with(['group', 'inviter'])
                ->where('email', $user->email)
                ->where('status', 'pending')
                ->where(function ($query) {
                    $query->whereNull('expires_at')->orWhere('expires_at', '>', now());
                })
                ->latest()
                ->get()
            : collect();

        return view('groups.index', compact('groups', 'invitations'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'description' => 'nullable|string|max:1000',
        ]);

        $group = CollaborationGroup::create([
            'owner_id' => auth()->id(),
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        $group->members()->attach(auth()->id(), [
            'role' => 'owner',
            'joined_at' => now(),
        ]);

        return redirect()->route('groups.show', $group)->with('success', 'Group created.');
    }

    public function show(CollaborationGroup $group)
    {
        Gate::authorize('view', $group);

        $group->load([
            'owner',
            'members',
            'invitations' => fn ($query) => $query->latest(),
            'tasks' => fn ($query) => $query
                ->with(['subject', 'assignee'])
                ->withCount('completedPomodoroSessions')
                ->withSum('completedPomodoroSessions', 'duration')
                ->orderBy('due_date'),
        ]);

        $subjects = auth()->user()->subjects()->get();
        $canManage = $group->isAdmin(auth()->user());

        return view('groups.show', compact('group', 'subjects', 'canManage'));
    }

    public function destroy(CollaborationGroup $group)
    {
        Gate::authorize('delete', $group);

        $group->delete();

        return redirect()->route('groups.index')->with('success', 'Group deleted.');
    }
}
