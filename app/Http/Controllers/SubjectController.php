<?php
// app/Http/Controllers/SubjectController.php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = auth()->user()->subjects()->withCount(['tasks','notes','flashcards'])->get();
        return view('subjects.index', compact('subjects'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'color'       => 'required|string|size:7',
            'description' => 'nullable|string|max:500',
        ]);

        auth()->user()->subjects()->create($data);
        return back()->with('success', 'Subject created!');
    }

    public function update(Request $request, Subject $subject)
    {
        $this->authorize('update', $subject);
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'color'       => 'required|string|size:7',
            'description' => 'nullable|string|max:500',
        ]);
        $subject->update($data);
        return back()->with('success', 'Subject updated!');
    }

    public function destroy(Subject $subject)
    {
        $this->authorize('delete', $subject);
        $subject->delete();
        return back()->with('success', 'Subject deleted.');
    }
}
