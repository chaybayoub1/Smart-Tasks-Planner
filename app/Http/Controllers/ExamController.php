<?php
// app/Http/Controllers/ExamController.php

namespace App\Http\Controllers;

use App\Models\Exam;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index()
    {
        $user     = auth()->user();
        $subjects = $user->subjects()->get();

        $upcoming = $user->exams()
            ->with('subject')
            ->where('exam_date', '>=', now())
            ->orderBy('exam_date')
            ->get();

        $past = $user->exams()
            ->with('subject')
            ->where('exam_date', '<', now())
            ->orderByDesc('exam_date')
            ->take(5)
            ->get();

        return view('exams.index', compact('upcoming', 'past', 'subjects'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'      => 'required|string|max:200',
            'subject_id' => 'nullable|exists:subjects,id',
            'exam_date'  => 'required|date|after:now',
            'location'   => 'nullable|string|max:200',
            'notes'      => 'nullable|string',
        ]);

        auth()->user()->exams()->create($data);
        return back()->with('success', 'Exam added!');
    }

    public function edit(Exam $exam)
    {
        $this->authorize('update', $exam);
        $subjects = auth()->user()->subjects()->get();
        return view('exams.edit', compact('exam', 'subjects'));
    }

    public function update(Request $request, Exam $exam)
    {
        $this->authorize('update', $exam);
        $data = $request->validate([
            'title'      => 'required|string|max:200',
            'subject_id' => 'nullable|exists:subjects,id',
            'exam_date'  => 'required|date',
            'location'   => 'nullable|string|max:200',
            'notes'      => 'nullable|string',
        ]);
        $exam->update($data);
        return redirect()->route('exams.index')->with('success', 'Exam updated!');
    }

    public function destroy(Exam $exam)
    {
        $this->authorize('delete', $exam);
        $exam->delete();
        return back()->with('success', 'Exam deleted.');
    }
}
