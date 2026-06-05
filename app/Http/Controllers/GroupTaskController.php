<?php

namespace App\Http\Controllers;

use App\Models\CollaborationGroup;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class GroupTaskController extends Controller
{
    public function store(Request $request, CollaborationGroup $group)
    {
        Gate::authorize('manageMembers', $group);

        $memberIds = $group->members()->pluck('users.id')->all();

        $data = $request->validate([
            'title' => 'required|string|max:200',
            'subject_id' => 'nullable|exists:subjects,id',
            'assigned_to' => ['nullable', Rule::in($memberIds)],
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'priority' => 'required|in:low,medium,high',
        ]);

        $group->tasks()->create([
            'user_id' => auth()->id(),
            'assigned_to' => $data['assigned_to'] ?? null,
            'subject_id' => $data['subject_id'] ?? null,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'due_date' => $data['due_date'],
            'priority' => $data['priority'],
            'status' => 'pending',
        ]);

        return back()->with('success', 'Group task assigned.');
    }

    public function updateStatus(Request $request, CollaborationGroup $group, Task $task)
    {
        abort_unless((int) $task->group_id === (int) $group->id, 404);
        Gate::authorize('update', $task);

        $data = $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $task->update(['status' => $data['status']]);

        return back()->with('success', 'Task status updated.');
    }

    public function destroy(CollaborationGroup $group, Task $task)
    {
        abort_unless((int) $task->group_id === (int) $group->id, 404);
        Gate::authorize('delete', $task);

        $task->delete();

        return back()->with('success', 'Group task deleted.');
    }
}
