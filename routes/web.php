<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\PomodoroController;
use App\Http\Controllers\FlashcardController;
use App\Http\Controllers\ExamController;

// ── Public redirect ───────────────────────────────────────────
Route::get('/', fn() => redirect()->route('dashboard'));

// ── Auth routes (Breeze) ──────────────────────────────────────
require __DIR__ . '/auth.php';

// ── Protected routes ──────────────────────────────────────────
// Changed: 'verified' → 'email.verified'  (our custom middleware alias)
Route::middleware(['auth', 'email.verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Subjects
    Route::resource('subjects', SubjectController::class)->except(['show', 'create', 'edit']);

    // Tasks
    Route::resource('tasks', TaskController::class)->except(['show', 'create']);
    Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggleStatus'])->name('tasks.toggle');

    // Notes
    Route::resource('notes', NoteController::class)->except(['create']);
    Route::patch('/notes/{note}/pin', [NoteController::class, 'togglePin'])->name('notes.pin');

    // Pomodoro
    Route::get('/pomodoro',          [PomodoroController::class, 'index'])->name('pomodoro.index');
    Route::post('/pomodoro/session', [PomodoroController::class, 'store'])->name('pomodoro.store');

    // Flashcards
    Route::resource('flashcards', FlashcardController::class)->except(['show', 'create']);
    Route::get('/flashcards/review',                   [FlashcardController::class, 'review'])->name('flashcards.review');
    Route::patch('/flashcards/{flashcard}/difficulty', [FlashcardController::class, 'markDifficulty'])->name('flashcards.difficulty');

    // Exams
    Route::resource('exams', ExamController::class)->except(['show', 'create']);

});
