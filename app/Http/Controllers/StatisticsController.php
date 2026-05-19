<?php

namespace App\Http\Controllers;

use App\Services\ProductivityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatisticsController extends Controller
{
    public function __construct(
        private readonly ProductivityService $productivity
    ) {}

    public function index(Request $request)
    {
        $user = Auth::user();

        // Core metrics
        $taskStats = $this->productivity->getTaskStatistics($user);
        $studyStats = $this->productivity->getStudyStatistics($user);

        // Existing analytics
        $subjectDist = $this->productivity->getSubjectDistribution($user);
        $weeklyTasksChart = $this->productivity->getWeeklyTasksChart($user);
        $xpChart = $this->productivity->getXpProgressChart($user);

        // New analytics
        $heatmap = $this->productivity->getProductivityHeatmap($user);
        $weeklyComparison = $this->productivity->getWeeklyComparison($user);
        $subjectAnalytics = $this->productivity->getDetailedSubjectAnalytics($user);
        $focusAnalytics = $this->productivity->getFocusAnalytics($user);
        $trends = $this->productivity->getProductivityTrends($user);
        $insights = $this->productivity->generateStatisticsInsights($user);

        return view('statistics.index', compact(
            'taskStats',
            'studyStats',
            'subjectDist',
            'weeklyTasksChart',
            'xpChart',
            'heatmap',
            'weeklyComparison',
            'subjectAnalytics',
            'focusAnalytics',
            'trends',
            'insights'
        ));
    }
}