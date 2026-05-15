<?php
// app/Http/Controllers/FlashcardController.php

namespace App\Http\Controllers;

use App\Models\Flashcard;
use Illuminate\Http\Request;

class FlashcardController extends Controller
{
    public function index(Request $request)
    {
        $user     = auth()->user();
        $subjects = $user->subjects()->get();

        $query = $user->flashcards()->with('subject');

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        $flashcards  = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        $dueCount    = $user->flashcards()
            ->where(fn($q) => $q->whereNull('next_review_at')->orWhere('next_review_at', '<=', now()))
            ->count();

        return view('flashcards.index', compact('flashcards', 'subjects', 'dueCount'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'question'   => 'required|string',
            'answer'     => 'required|string',
            'subject_id' => 'nullable|exists:subjects,id',
        ]);

        auth()->user()->flashcards()->create($data);
        return back()->with('success', 'Flashcard created!');
    }

    public function edit(Flashcard $flashcard)
    {
        $this->authorize('update', $flashcard);
        $subjects = auth()->user()->subjects()->get();
        return view('flashcards.edit', compact('flashcard', 'subjects'));
    }

    public function update(Request $request, Flashcard $flashcard)
    {
        $this->authorize('update', $flashcard);
        $data = $request->validate([
            'question'   => 'required|string',
            'answer'     => 'required|string',
            'subject_id' => 'nullable|exists:subjects,id',
        ]);
        $flashcard->update($data);
        return redirect()->route('flashcards.index')->with('success', 'Flashcard updated!');
    }

    public function destroy(Flashcard $flashcard)
    {
        $this->authorize('delete', $flashcard);
        $flashcard->delete();
        return back()->with('success', 'Flashcard deleted.');
    }

    /**
     * Review mode — show cards due for review.
     */
    public function review(Request $request)
    {
        $user = auth()->user();

        $query = $user->flashcards()
            ->with('subject')
            ->where(fn($q) => $q->whereNull('next_review_at')->orWhere('next_review_at', '<=', now()));

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        $cards    = $query->inRandomOrder()->get();
        $subjects = $user->subjects()->get();

        return view('flashcards.review', compact('cards', 'subjects'));
    }

    /**
     * AJAX: Mark a card difficulty after review.
     */
    public function markDifficulty(Request $request, Flashcard $flashcard)
    {
        $this->authorize('update', $flashcard);
        $request->validate(['difficulty' => 'required|in:easy,medium,hard']);
        $flashcard->scheduleNextReview($request->difficulty);

        return response()->json(['success' => true, 'next_review' => $flashcard->next_review_at]);
    }
}
