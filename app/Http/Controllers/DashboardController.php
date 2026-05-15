<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Exam;
use App\Models\PomodoroSession;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Upcoming tasks (next 7 days, not completed)
        $upcomingTasks = $user->tasks()
            ->with('subject')
            ->where('status', '!=', 'completed')
            ->whereBetween('due_date', [now()->toDateString(), now()->addDays(7)->toDateString()])
            ->orderBy('due_date')
            ->take(5)
            ->get();

        // Upcoming exams
        $upcomingExams = $user->exams()
            ->with('subject')
            ->where('exam_date', '>=', now())
            ->orderBy('exam_date')
            ->take(3)
            ->get();

        // Streak
        $streak = $user->streak ?? $user->streak()->create([
            'current_streak'    => 0,
            'longest_streak'    => 0,
            'last_activity_date'=> null,
        ]);

        // Stats
        $totalMinutes   = $user->totalStudyMinutes();
        $totalSessions  = $user->pomodoroSessions()->where('completed', true)->count();
        $totalNotes     = $user->notes()->count();
        $totalFlashcards= $user->flashcards()->count();

        // Weekly chart data
        $weeklyData = $user->weeklyStudyMinutes();

        // Overdue tasks
        $overdueTasks = $user->tasks()
            ->where('status', '!=', 'completed')
            ->where('due_date', '<', now()->toDateString())
            ->count();

        // Flashcards due for review
        $flashcardsDue = $user->flashcards()
            ->where(function ($q) {
                $q->whereNull('next_review_at')
                  ->orWhere('next_review_at', '<=', now());
            })->count();

        // Badges
        $badges = $user->badges()->orderByPivot('earned_at', 'desc')->take(6)->get();

        return view('dashboard.index', compact(
            'user', 'upcomingTasks', 'upcomingExams', 'streak',
            'totalMinutes', 'totalSessions', 'totalNotes', 'totalFlashcards',
            'weeklyData', 'overdueTasks', 'flashcardsDue', 'badges'
        ));
    }
}
