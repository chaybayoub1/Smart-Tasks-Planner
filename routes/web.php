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
<<<<<<< HEAD
use App\Http\Controllers\StatisticsController;
<<<<<<< HEAD
=======
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudyGroupController;
>>>>>>> hiba
=======
use App\Http\Controllers\CollaborationGroupController;
use App\Http\Controllers\CollaborationInvitationController;
use App\Http\Controllers\GroupTaskController;
>>>>>>> 0b3d2f61a2ecc3f5b1c284ed789be298c8eb636d

// ── Public redirect ───────────────────────────────────────────
Route::get('/', fn() => redirect()->route('dashboard'));

// ── Auth routes (Breeze) ──────────────────────────────────────
require __DIR__ . '/auth.php';

// ── Protected routes ──────────────────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

<<<<<<< HEAD
    // Subjects
=======
    // ── Profile ───────────────────────────────────────────────
    Route::get('/profile',             [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',           [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password',    [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile',          [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ── Subjects ──────────────────────────────────────────────
>>>>>>> hiba
    Route::resource('subjects', SubjectController::class)->except(['show', 'create', 'edit']);

    // ── Tasks ─────────────────────────────────────────────────
    Route::resource('tasks', TaskController::class)->except(['show', 'create']);
    Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggleStatus'])->name('tasks.toggle');

    // ── Notes ─────────────────────────────────────────────────
    Route::resource('notes', NoteController::class)->except(['create']);
    Route::patch('/notes/{note}/pin', [NoteController::class, 'togglePin'])->name('notes.pin');

<<<<<<< HEAD
    // Pomodoro
=======
    // ── Pomodoro ──────────────────────────────────────────────
>>>>>>> hiba
    Route::get('/pomodoro',          [PomodoroController::class, 'index'])->name('pomodoro.index');
    Route::post('/pomodoro/session', [PomodoroController::class, 'store'])->name('pomodoro.store');

    // ── Flashcards ────────────────────────────────────────────
    Route::resource('flashcards', FlashcardController::class)->except(['show', 'create']);
    Route::get('/flashcards/review',                   [FlashcardController::class, 'review'])->name('flashcards.review');
    Route::patch('/flashcards/{flashcard}/difficulty', [FlashcardController::class, 'markDifficulty'])->name('flashcards.difficulty');

    // ── Exams ─────────────────────────────────────────────────
    Route::resource('exams', ExamController::class)->except(['show', 'create']);

<<<<<<< HEAD
    // ── Statistics ────────────────────────────────────────────
    Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics.index');

<<<<<<< HEAD
=======
    // ── Collaboration ─────────────────────────────────────────
    Route::prefix('collaboration')->name('collaboration.')->group(function () {
        Route::get('/',                                              [StudyGroupController::class, 'index'])->name('index');
        Route::post('/',                                             [StudyGroupController::class, 'store'])->name('store');
        Route::get('/{studyGroup}',                                  [StudyGroupController::class, 'show'])->name('show');
        Route::delete('/{studyGroup}',                               [StudyGroupController::class, 'destroy'])->name('destroy');
        Route::post('/join',                                         [StudyGroupController::class, 'join'])->name('join');
        Route::post('/{studyGroup}/leave',                           [StudyGroupController::class, 'leave'])->name('leave');
        Route::post('/{studyGroup}/message',                         [StudyGroupController::class, 'sendMessage'])->name('message');
        Route::post('/{studyGroup}/tasks',                           [StudyGroupController::class, 'storeTask'])->name('tasks.store');
        Route::patch('/{studyGroup}/tasks/{groupTask}/toggle',       [StudyGroupController::class, 'toggleTask'])->name('tasks.toggle');
        Route::post('/{studyGroup}/regenerate-code',                 [StudyGroupController::class, 'regenerateCode'])->name('regenerate-code');
    });
>>>>>>> hiba
=======
    // Collaboration
    Route::get('/groups', [CollaborationGroupController::class, 'index'])->name('groups.index');
    Route::post('/groups', [CollaborationGroupController::class, 'store'])->name('groups.store');
    Route::get('/groups/{group}', [CollaborationGroupController::class, 'show'])->name('groups.show');
    Route::delete('/groups/{group}', [CollaborationGroupController::class, 'destroy'])->name('groups.destroy');
    Route::post('/groups/{group}/invitations', [CollaborationInvitationController::class, 'store'])->name('groups.invitations.store');
    Route::get('/group-invitations/{token}/accept', [CollaborationInvitationController::class, 'accept'])->name('groups.invitations.respond');
    Route::post('/group-invitations/{token}/decline', [CollaborationInvitationController::class, 'decline'])->name('groups.invitations.decline');
    Route::post('/groups/{group}/tasks', [GroupTaskController::class, 'store'])->name('groups.tasks.store');
    Route::patch('/groups/{group}/tasks/{task}/status', [GroupTaskController::class, 'updateStatus'])->name('groups.tasks.status');
    Route::delete('/groups/{group}/tasks/{task}', [GroupTaskController::class, 'destroy'])->name('groups.tasks.destroy');

>>>>>>> 0b3d2f61a2ecc3f5b1c284ed789be298c8eb636d
});
