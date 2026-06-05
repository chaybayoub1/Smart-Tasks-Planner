<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Services\InsightService;
use App\Services\ProductivityService;

class DashboardController extends Controller
{
    public function __construct(
        protected readonly ProductivityService $productivity,
        protected readonly InsightService      $insight,
    ) {}

    public function index()
    {
        $user = auth()->user();

        // ── Delegated to ProductivityService ─────────────────────────────────
        $taskStats          = $this->productivity->getTaskStatistics($user);
        $studyStats         = $this->productivity->getStudyStatistics($user);
        $weeklyChart        = $this->productivity->getWeeklyTasksChart($user);
        $xpChart            = $this->productivity->getXpProgressChart($user);
        $subjectDistribution = $this->productivity->getSubjectDistribution($user);

        // ── Dashboard-specific queries ────────────────────────────────────────

        $upcomingTasks = $user->tasks()
            ->with('subject')
            ->withCount('completedPomodoroSessions')
            ->withSum('completedPomodoroSessions', 'duration')
            ->whereNull('group_id')
            ->where('status', '!=', 'completed')
            ->whereBetween('due_date', [now()->toDateString(), now()->addDays(7)->toDateString()])
            ->orderBy('due_date')
            ->take(5)
            ->get();

        $upcomingExams = $user->exams()
            ->with('subject')
            ->where('exam_date', '>=', now())
            ->orderBy('exam_date')
            ->take(3)
            ->get();

        $streak = $user->streak ?? $user->streak()->create([
            'current_streak'     => 0,
            'longest_streak'     => 0,
            'last_activity_date' => null,
        ]);

        $totalNotes      = $user->notes()->count();
        $totalFlashcards = $user->flashcards()->count();

        $flashcardsDue = $user->flashcards()
            ->where(function ($q) {
                $q->whereNull('next_review_at')
                  ->orWhere('next_review_at', '<=', now());
            })->count();

        $badges = $user->badges()->orderByPivot('earned_at', 'desc')->take(6)->get();

        // ── Smart Insight ─────────────────────────────────────────────────────
        // Returns: [ 'emoji' => '💡', 'message' => '...', 'tone' => 'positive' ]
        $smartInsight = $this->insight->generate($taskStats, $studyStats, $streak);

        return view('dashboard.index', compact(
            'user',
            'upcomingTasks',
            'upcomingExams',
            'streak',
            'taskStats',
            'studyStats',
            'weeklyChart',
            'xpChart',
            'subjectDistribution',
            'totalNotes',
            'totalFlashcards',
            'flashcardsDue',
            'badges',
            'smartInsight',
        ));
    }
}
