<?php
// app/Http/Controllers/NoteController.php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $user     = auth()->user();
        $subjects = $user->subjects()->get();

        $query = $user->notes()->with('subject');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(fn($s) =>
                $s->where('title', 'like', "%{$q}%")
                  ->orWhere('content', 'like', "%{$q}%")
            );
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        // Pinned first
        $notes = $query->orderByDesc('is_pinned')->orderByDesc('updated_at')->paginate(12)->withQueryString();

        return view('notes.index', compact('notes', 'subjects'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'      => 'required|string|max:200',
            'subject_id' => 'nullable|exists:subjects,id',
            'content'    => 'required|string',
            'is_pinned'  => 'boolean',
        ]);
        $data['is_pinned'] = $request->boolean('is_pinned');

        auth()->user()->notes()->create($data);
        return back()->with('success', 'Note saved!');
    }

    public function edit(Note $note)
    {
        $this->authorize('update', $note);
        $subjects = auth()->user()->subjects()->get();
        return view('notes.edit', compact('note', 'subjects'));
    }

    public function update(Request $request, Note $note)
    {
        $this->authorize('update', $note);

        $data = $request->validate([
            'title'      => 'required|string|max:200',
            'subject_id' => 'nullable|exists:subjects,id',
            'content'    => 'required|string',
            'is_pinned'  => 'boolean',
        ]);
        $data['is_pinned'] = $request->boolean('is_pinned');

        $note->update($data);
        return redirect()->route('notes.index')->with('success', 'Note updated!');
    }

    public function destroy(Note $note)
    {
        $this->authorize('delete', $note);
        $note->delete();
        return back()->with('success', 'Note deleted.');
    }

    public function show(Note $note)
    {
        $this->authorize('view', $note);
        return view('notes.show', compact('note'));
    }

    public function togglePin(Note $note)
    {
        $this->authorize('update', $note);
        $note->is_pinned = !$note->is_pinned;
        $note->save();
        return back();
    }
}
