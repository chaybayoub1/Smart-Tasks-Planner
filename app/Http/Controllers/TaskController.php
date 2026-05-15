<?php
// app/Http/Controllers/TaskController.php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Gate;  // ← ajoute cette ligne
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $user     = auth()->user();
        $subjects = $user->subjects()->get();

        $query = $user->tasks()->with('subject');

        // Filters
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $tasks = $query->orderBy('due_date')->paginate(12)->withQueryString();

        // Calendar grouping (current month)
        $month      = $request->get('month', now()->month);
        $year       = $request->get('year', now()->year);
        $calTasks   = $user->tasks()
            ->whereMonth('due_date', $month)
            ->whereYear('due_date', $year)
            ->with('subject')
            ->get()
            ->groupBy(fn($t) => $t->due_date->day);

        return view('tasks.index', compact('tasks', 'subjects', 'calTasks', 'month', 'year'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:200',
            'subject_id'  => 'nullable|exists:subjects,id',
            'description' => 'nullable|string',
            'due_date'    => 'required|date',
            'duration'    => 'required|integer|min:1|max:1440',
            'priority'    => 'required|in:low,medium,high',
        ]);

        auth()->user()->tasks()->create($data);
        return back()->with('success', 'Task added!');
    }

    public function edit(Task $task)
    {
        Gate::authorize('update', $task);
        $subjects = auth()->user()->subjects()->get();
        return view('tasks.edit', compact('task', 'subjects'));
    }

    public function update(Request $request, Task $task)
    {
        Gate::authorize('update', $task);

        $data = $request->validate([
            'title'       => 'required|string|max:200',
            'subject_id'  => 'nullable|exists:subjects,id',
            'description' => 'nullable|string',
            'due_date'    => 'required|date',
            'duration'    => 'required|integer|min:1|max:1440',
            'priority'    => 'required|in:low,medium,high',
            'status'      => 'required|in:pending,in_progress,completed',
        ]);

        $task->update($data);
        return redirect()->route('tasks.index')->with('success', 'Task updated!');
    }

    public function destroy(Task $task)
    {
       Gate::authorize('delete', $task);
        $task->delete();
        return back()->with('success', 'Task deleted.');
    }

    public function toggleStatus(Task $task)
    {
       Gate::authorize('update', $task);
        $task->status = $task->status === 'completed' ? 'pending' : 'completed';
        $task->save();
        return back()->with('success', 'Task status updated.');
    }
}
