<?php

namespace App\Http\Controllers;

use App\Services\ProductivityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StatisticsController extends Controller
{
    public function __construct(
        protected readonly ProductivityService $productivity
    ) {}

    // -------------------------------------------------------------------------
    // Main page
    // -------------------------------------------------------------------------

    /**
     * Render the full statistics dashboard.
     * All data is passed to the Blade view and also serialised as JSON for Chart.js.
     */
    public function index(): View
    {
        $user = Auth::user();

        $taskStats         = $this->productivity->getTaskStatistics($user);
        $studyStats        = $this->productivity->getStudyStatistics($user);
        $weeklyTasksChart  = $this->productivity->getWeeklyTasksChart($user);
        $xpProgressChart   = $this->productivity->getXpProgressChart($user);
        $weeklyComparison  = $this->productivity->getWeeklyComparison($user);
        $subjectChart      = $this->productivity->getSubjectProductivity($user);
        $heatmapData       = $this->productivity->getHeatmapData($user);

        return view('statistics.index', compact(
            'taskStats',
            'studyStats',
            'weeklyTasksChart',
            'xpProgressChart',
            'weeklyComparison',
            'subjectChart',
            'heatmapData',
        ));
    }

    // -------------------------------------------------------------------------
    // JSON endpoints (AJAX / future API consumption)
    // -------------------------------------------------------------------------

    public function tasks(): JsonResponse
    {
        return response()->json(
            $this->productivity->getTaskStatistics(Auth::user())
        );
    }

    public function studyTime(): JsonResponse
    {
        return response()->json(
            $this->productivity->getStudyStatistics(Auth::user())
        );
    }

    public function xp(): JsonResponse
    {
        return response()->json(
            $this->productivity->getXpProgressChart(Auth::user())
        );
    }

    public function streaks(): JsonResponse
    {
        // Streak data lives on the user's related Streak model.
        $streak = Auth::user()->streak;

        return response()->json([
            'current_streak' => $streak?->current_streak  ?? 0,
            'longest_streak' => $streak?->longest_streak  ?? 0,
            'last_study_date'=> $streak?->last_study_date ?? null,
        ]);
    }

    public function subjects(): JsonResponse
    {
        return response()->json(
            $this->productivity->getSubjectProductivity(Auth::user())
        );
    }

    public function heatmap(): JsonResponse
    {
        return response()->json(
            $this->productivity->getHeatmapData(Auth::user())
        );
    }

    public function weeklyComparison(): JsonResponse
    {
        return response()->json(
            $this->productivity->getWeeklyComparison(Auth::user())
        );
    }

    public function pomodoro(): JsonResponse
    {
        return response()->json(
            $this->productivity->getStudyStatistics(Auth::user())
        );
    }
}
