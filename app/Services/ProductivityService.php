<?php

namespace App\Services;

use App\Models\PomodoroSession;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProductivityService
{
    // -------------------------------------------------------------------------
    // 1. TASK STATISTICS
    // -------------------------------------------------------------------------

    public function getTaskStatistics(mixed $user): array
    {
        $counts = Task::query()
            ->where('user_id', $user->id)
            ->selectRaw("
                COUNT(*) AS total,
                SUM(status = 'completed') AS completed,
                SUM(status = 'pending') AS pending,
                SUM(status = 'in_progress') AS in_progress,
                SUM(status != 'completed' AND due_date < NOW()) AS overdue
            ")
            ->first();

        $total       = (int) ($counts->total      ?? 0);
        $completed   = (int) ($counts->completed  ?? 0);
        $pending     = (int) ($counts->pending    ?? 0);
        $in_progress = (int) ($counts->in_progress ?? 0);
        $overdue     = (int) ($counts->overdue    ?? 0);

        return [
            'total'                  => $total,
            'completed'              => $completed,
            'pending'                => $pending,
            'in_progress'            => $in_progress,
            'overdue'                => $overdue,
            'completion_rate'        => $total > 0 ? round(($completed   / $total) * 100, 1) : 0.0,
            'overdue_rate'           => $total > 0 ? round(($overdue     / $total) * 100, 1) : 0.0,
            'overdue_percentage'     => $total > 0 ? round(($overdue     / $total) * 100, 1) : 0.0,
            'pending_percentage'     => $total > 0 ? round(($pending     / $total) * 100, 1) : 0.0,
            'in_progress_percentage' => $total > 0 ? round(($in_progress / $total) * 100, 1) : 0.0,
            'completed_percentage'   => $total > 0 ? round(($completed   / $total) * 100, 1) : 0.0,
        ];
    }

    // -------------------------------------------------------------------------
    // 2. STUDY / POMODORO STATISTICS
    // -------------------------------------------------------------------------

    public function getStudyStatistics(mixed $user): array
    {
        $weekStart = Carbon::now()->startOfWeek();

        $allTime = PomodoroSession::query()
            ->where('user_id', $user->id)
            ->where('type', 'focus')
            ->where('completed', true)
            ->selectRaw('COUNT(*) AS sessions, SUM(duration) AS minutes, SUM(xp_earned) AS xp')
            ->first();

        $thisWeek = PomodoroSession::query()
            ->where('user_id', $user->id)
            ->where('type', 'focus')
            ->where('completed', true)
            ->where('created_at', '>=', $weekStart)
            ->selectRaw('COUNT(*) AS sessions, SUM(duration) AS minutes, SUM(xp_earned) AS xp')
            ->first();

        $totalSessions = (int) ($allTime->sessions ?? 0);
        $totalMinutes  = (int) ($allTime->minutes  ?? 0);

        return [
            'weekly_study_minutes' => (int) ($thisWeek->minutes  ?? 0),
            'total_study_minutes'  => $totalMinutes,
            'weekly_xp'            => (int) ($thisWeek->xp       ?? 0),
            'total_xp'             => (int) ($allTime->xp        ?? 0),
            'weekly_sessions'      => (int) ($thisWeek->sessions ?? 0),
            'total_sessions'       => $totalSessions,
            'avg_session_minutes'  => $totalSessions > 0
                                        ? round($totalMinutes / $totalSessions, 1)
                                        : 0.0,
        ];
    }

    // -------------------------------------------------------------------------
    // 3. WEEKLY TASKS CHART
    // -------------------------------------------------------------------------

    public function getWeeklyTasksChart(mixed $user): array
    {
        $days = collect(range(6, 0))->map(fn ($i) => Carbon::today()->subDays($i));

        $rows = Task::query()
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->where('updated_at', '>=', Carbon::today()->subDays(6)->startOfDay())
            ->selectRaw("DATE(updated_at) AS day, COUNT(*) AS total")
            ->groupByRaw("DATE(updated_at)")
            ->pluck('total', 'day')
            ->mapWithKeys(fn ($v, $k) => [Carbon::parse($k)->toDateString() => $v]);

        $labels = [];
        $data   = [];

        foreach ($days as $day) {
            $labels[] = $day->format('D');
            $data[]   = (int) ($rows[$day->toDateString()] ?? 0);
        }

        return compact('labels', 'data');
    }

    // -------------------------------------------------------------------------
    // 4. XP PROGRESS CHART
    // -------------------------------------------------------------------------

    public function getXpProgressChart(mixed $user): array
    {
        $days = collect(range(29, 0))->map(fn ($i) => Carbon::today()->subDays($i));

        $rows = PomodoroSession::query()
            ->where('user_id', $user->id)
            ->where('type', 'focus')
            ->where('completed', true)
            ->where('created_at', '>=', Carbon::today()->subDays(29)->startOfDay())
            ->selectRaw("DATE(created_at) AS day, SUM(xp_earned) AS xp")
            ->groupByRaw("DATE(created_at)")
            ->pluck('xp', 'day')
            ->mapWithKeys(fn ($v, $k) => [Carbon::parse($k)->toDateString() => $v]);

        $labels     = [];
        $data       = [];
        $cumulative = 0;

        foreach ($days as $day) {
            $labels[]    = $day->format('M j');
            $cumulative += (int) ($rows[$day->toDateString()] ?? 0);
            $data[]      = $cumulative;
        }

        return compact('labels', 'data');
    }

    // -------------------------------------------------------------------------
    // 5. HEATMAP DATA (legacy — kept for dashboard)
    // -------------------------------------------------------------------------

    public function getHeatmapData(mixed $user): array
    {
        $from = Carbon::today()->subDays(364)->startOfDay();

        return PomodoroSession::query()
            ->where('user_id', $user->id)
            ->where('type', 'focus')
            ->where('completed', true)
            ->where('created_at', '>=', $from)
            ->selectRaw("DATE(created_at) AS day, COUNT(*) AS total")
            ->groupByRaw("DATE(created_at)")
            ->pluck('total', 'day')
            ->mapKeys(fn ($v, $k) => Carbon::parse($k)->toDateString())
            ->toArray();
    }

    // -------------------------------------------------------------------------
    // 6. WEEKLY COMPARISON
    // -------------------------------------------------------------------------

    public function getWeeklyComparison(mixed $user): array
    {
        $thisStart = Carbon::now()->startOfWeek();
        $thisEnd   = Carbon::now()->endOfWeek();
        $prevStart = (clone $thisStart)->subWeek();
        $prevEnd   = (clone $thisEnd)->subWeek();

        $completedThis = Task::where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereBetween('updated_at', [$thisStart, $thisEnd])
            ->count();

        $completedPrev = Task::where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereBetween('updated_at', [$prevStart, $prevEnd])
            ->count();

        $pomoCols = fn ($start, $end) => PomodoroSession::where('user_id', $user->id)
            ->where('type', 'focus')
            ->where('completed', true)
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('COUNT(*) AS sessions, SUM(duration) AS minutes, SUM(xp_earned) AS xp')
            ->first();

        $pomoThis = $pomoCols($thisStart, $thisEnd);
        $pomoPrev = $pomoCols($prevStart, $prevEnd);

        return [
            'tasks_completed'   => $this->buildDelta((int)$completedThis, (int)$completedPrev),
            'study_minutes'     => $this->buildDelta((int)($pomoThis->minutes  ?? 0), (int)($pomoPrev->minutes  ?? 0)),
            'xp_earned'         => $this->buildDelta((int)($pomoThis->xp       ?? 0), (int)($pomoPrev->xp       ?? 0)),
            'pomodoro_sessions' => $this->buildDelta((int)($pomoThis->sessions ?? 0), (int)($pomoPrev->sessions ?? 0)),
        ];
    }

    // -------------------------------------------------------------------------
    // 7. SUBJECT PRODUCTIVITY
    // -------------------------------------------------------------------------

    public function getSubjectProductivity(mixed $user): array
    {
        $tasksBySubject = Task::query()
            ->where('tasks.user_id', $user->id)
            ->where('tasks.status', 'completed')
            ->whereNotNull('tasks.subject_id')
            ->join('subjects', 'subjects.id', '=', 'tasks.subject_id')
            ->selectRaw('subjects.name AS subject_name, COUNT(*) AS total')
            ->groupBy('subjects.id', 'subjects.name')
            ->pluck('total', 'subject_name');

        $minutesBySubject = PomodoroSession::query()
            ->where('pomodoro_sessions.user_id', $user->id)
            ->where('pomodoro_sessions.type', 'focus')
            ->where('pomodoro_sessions.completed', true)
            ->whereNotNull('pomodoro_sessions.subject_id')
            ->join('subjects', 'subjects.id', '=', 'pomodoro_sessions.subject_id')
            ->selectRaw('subjects.name AS subject_name, SUM(pomodoro_sessions.duration) AS minutes')
            ->groupBy('subjects.id', 'subjects.name')
            ->pluck('minutes', 'subject_name');

        $names = $tasksBySubject->keys()
            ->merge($minutesBySubject->keys())
            ->unique()
            ->values();

        return [
            'labels'  => $names->all(),
            'tasks'   => $names->map(fn ($n) => (int) ($tasksBySubject[$n]   ?? 0))->all(),
            'minutes' => $names->map(fn ($n) => (int) ($minutesBySubject[$n] ?? 0))->all(),
        ];
    }

    // -------------------------------------------------------------------------
    // 8. SUBJECT DISTRIBUTION
    // -------------------------------------------------------------------------

    public function getSubjectDistribution(mixed $user): array
    {
        $palette = [
            '#6366f1','#10b981','#f59e0b','#0ea5e9','#f43f5e',
            '#8b5cf6','#06b6d4','#84cc16','#fb923c','#ec4899',
        ];

        $rows = Task::query()
            ->where('tasks.user_id', $user->id)
            ->whereNotNull('tasks.subject_id')
            ->join('subjects', 'subjects.id', '=', 'tasks.subject_id')
            ->selectRaw('subjects.name AS subject_name, COUNT(*) AS total')
            ->groupBy('subjects.id', 'subjects.name')
            ->orderByDesc('total')
            ->get();

        if ($rows->isEmpty()) {
            return ['labels'=>[],'data'=>[],'percentages'=>[],'colors'=>[],'top_subject'=>null,'total'=>0];
        }

        $grandTotal  = $rows->sum('total');
        $labels = $data = $percentages = $colors = [];

        foreach ($rows as $i => $row) {
            $labels[]      = $row->subject_name;
            $data[]        = (int) $row->total;
            $percentages[] = $grandTotal > 0 ? round(($row->total / $grandTotal) * 100, 1) : 0.0;
            $colors[]      = $palette[$i % count($palette)];
        }

        return compact('labels','data','percentages','colors','grandTotal') + [
            'top_subject' => $labels[0] ?? null,
            'total'       => (int) $grandTotal,
        ];
    }

    // =========================================================================
    // NEW STATISTICS MODULE METHODS
    // =========================================================================

    // -------------------------------------------------------------------------
    // A. PRODUCTIVITY HEATMAP — GitHub-style, last 365 days
    // -------------------------------------------------------------------------

    /**
     * Returns daily activity data for a GitHub-style heatmap.
     * Combines completed tasks + focus sessions per day.
     *
     * @return array{dates: array<string, int>, max: int, weeks: array}
     */
    public function getProductivityHeatmap(mixed $user): array
    {
        $from = Carbon::today()->subDays(364)->startOfDay();

        // Focus sessions per day
        $sessions = PomodoroSession::query()
            ->where('user_id', $user->id)
            ->where('type', 'focus')
            ->where('completed', true)
            ->where('created_at', '>=', $from)
            ->selectRaw("DATE(created_at) AS day, COUNT(*) AS cnt")
            ->groupByRaw("DATE(created_at)")
            ->pluck('cnt', 'day');

        // Completed tasks per day
        $tasks = Task::query()
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->where('updated_at', '>=', $from)
            ->selectRaw("DATE(updated_at) AS day, COUNT(*) AS cnt")
            ->groupByRaw("DATE(updated_at)")
            ->pluck('cnt', 'day');

        // Merge both counts
        $dates = [];
        $allKeys = collect($sessions->keys())->merge($tasks->keys())->unique();
        foreach ($allKeys as $day) {
            $dates[$day] = (int)($sessions[$day] ?? 0) + (int)($tasks[$day] ?? 0);
        }

        $max = empty($dates) ? 1 : max($dates);

        // Build weeks array for the grid renderer
        $weeks   = [];
        $current = Carbon::parse($from)->startOfWeek(Carbon::SUNDAY);
        $today   = Carbon::today();

        while ($current->lte($today)) {
            $week = [];
            for ($d = 0; $d < 7; $d++) {
                $dateStr = $current->toDateString();
                $week[]  = [
                    'date'  => $dateStr,
                    'count' => $dates[$dateStr] ?? 0,
                    'level' => $this->heatLevel($dates[$dateStr] ?? 0, $max),
                    'future'=> $current->gt($today),
                ];
                $current->addDay();
            }
            $weeks[] = $week;
        }

        return compact('dates', 'max', 'weeks');
    }

    // -------------------------------------------------------------------------
    // B. DETAILED SUBJECT ANALYTICS
    // -------------------------------------------------------------------------

    public function getDetailedSubjectAnalytics(mixed $user): array
    {
        // Completed vs total tasks per subject
        $taskRows = Task::query()
            ->where('tasks.user_id', $user->id)
            ->whereNotNull('tasks.subject_id')
            ->join('subjects', 'subjects.id', '=', 'tasks.subject_id')
            ->selectRaw("
                subjects.id AS subject_id,
                subjects.name AS subject_name,
                COUNT(*) AS total_tasks,
                SUM(tasks.status = 'completed') AS completed_tasks
            ")
            ->groupBy('subjects.id', 'subjects.name')
            ->get();

        // Study minutes per subject
        $minuteRows = PomodoroSession::query()
            ->where('pomodoro_sessions.user_id', $user->id)
            ->where('pomodoro_sessions.type', 'focus')
            ->where('pomodoro_sessions.completed', true)
            ->whereNotNull('pomodoro_sessions.subject_id')
            ->join('subjects', 'subjects.id', '=', 'pomodoro_sessions.subject_id')
            ->selectRaw("subjects.id AS subject_id, SUM(pomodoro_sessions.duration) AS study_minutes")
            ->groupBy('subjects.id')
            ->pluck('study_minutes', 'subject_id');

        if ($taskRows->isEmpty()) {
            return [
                'subjects'        => [],
                'strongest'       => null,
                'weakest'         => null,
                'chart_labels'    => [],
                'chart_rates'     => [],
                'chart_minutes'   => [],
            ];
        }

        $subjects = $taskRows->map(function ($row) use ($minuteRows) {
            $total     = (int) $row->total_tasks;
            $completed = (int) $row->completed_tasks;
            $rate      = $total > 0 ? round(($completed / $total) * 100, 1) : 0.0;
            return [
                'name'            => $row->subject_name,
                'total_tasks'     => $total,
                'completed_tasks' => $completed,
                'completion_rate' => $rate,
                'study_minutes'   => (int) ($minuteRows[$row->subject_id] ?? 0),
            ];
        })->values()->toArray();

        // Strongest = highest completion rate (min 1 task)
        $withTasks  = array_filter($subjects, fn ($s) => $s['total_tasks'] > 0);
        $strongest  = !empty($withTasks)
            ? array_reduce($withTasks, fn ($carry, $s) => ($carry === null || $s['completion_rate'] > $carry['completion_rate']) ? $s : $carry)
            : null;
        $weakest    = !empty($withTasks)
            ? array_reduce($withTasks, fn ($carry, $s) => ($carry === null || $s['completion_rate'] < $carry['completion_rate']) ? $s : $carry)
            : null;

        return [
            'subjects'      => $subjects,
            'strongest'     => $strongest,
            'weakest'       => $weakest,
            'chart_labels'  => array_column($subjects, 'name'),
            'chart_rates'   => array_column($subjects, 'completion_rate'),
            'chart_minutes' => array_column($subjects, 'study_minutes'),
        ];
    }

    // -------------------------------------------------------------------------
    // C. FOCUS ANALYTICS
    // -------------------------------------------------------------------------

    public function getFocusAnalytics(mixed $user): array
    {
        $sessions = PomodoroSession::query()
            ->where('user_id', $user->id)
            ->where('type', 'focus')
            ->where('completed', true)
            ->selectRaw("
                COUNT(*) AS total_sessions,
                SUM(duration) AS total_minutes,
                AVG(duration) AS avg_minutes,
                HOUR(created_at) AS hour
            ")
            ->groupByRaw("HOUR(created_at)")
            ->get();

        $totalSessions = $sessions->sum('total_sessions');
        $totalMinutes  = $sessions->sum('total_minutes');

        // Best study hour = hour with most sessions
        $bestHourRow   = $sessions->sortByDesc('total_sessions')->first();
        $bestHour      = $bestHourRow ? (int)$bestHourRow->hour : null;
        $bestHourLabel = $bestHour !== null
            ? Carbon::createFromTime($bestHour)->format('g A') . ' – ' . Carbon::createFromTime(($bestHour + 1) % 24)->format('g A')
            : 'N/A';

        $avgSession = $totalSessions > 0 ? round($totalMinutes / $totalSessions, 1) : 0.0;

        // Focus consistency: days with at least 1 session in last 30 days
        $activeDays = PomodoroSession::query()
            ->where('user_id', $user->id)
            ->where('type', 'focus')
            ->where('completed', true)
            ->where('created_at', '>=', Carbon::today()->subDays(29))
            ->selectRaw("DATE(created_at) AS day")
            ->groupByRaw("DATE(created_at)")
            ->count();

        $consistency = round(($activeDays / 30) * 100, 1);

        // Hourly distribution for chart
        $hourlyLabels = [];
        $hourlyData   = [];
        $hourMap      = $sessions->keyBy('hour');
        for ($h = 0; $h < 24; $h++) {
            $hourlyLabels[] = Carbon::createFromTime($h)->format('g A');
            $hourlyData[]   = (int) ($hourMap[$h]->total_sessions ?? 0);
        }

        return [
            'total_sessions'   => (int) $totalSessions,
            'total_minutes'    => (int) $totalMinutes,
            'avg_session'      => $avgSession,
            'best_hour'        => $bestHourLabel,
            'consistency'      => $consistency,
            'active_days'      => $activeDays,
            'hourly_labels'    => $hourlyLabels,
            'hourly_data'      => $hourlyData,
        ];
    }

    // -------------------------------------------------------------------------
    // D. PRODUCTIVITY TRENDS — monthly, last 6 months
    // -------------------------------------------------------------------------

    public function getProductivityTrends(mixed $user): array
    {
        $months = collect(range(5, 0))->map(fn ($i) => Carbon::now()->startOfMonth()->subMonths($i));

        $labels          = [];
        $tasksData       = [];
        $minutesData     = [];
        $xpData          = [];

        foreach ($months as $month) {
            $start = $month->copy()->startOfMonth();
            $end   = $month->copy()->endOfMonth();

            $labels[] = $month->format('M Y');

            $tasksData[] = Task::where('user_id', $user->id)
                ->where('status', 'completed')
                ->whereBetween('updated_at', [$start, $end])
                ->count();

            $pomo = PomodoroSession::where('user_id', $user->id)
                ->where('type', 'focus')
                ->where('completed', true)
                ->whereBetween('created_at', [$start, $end])
                ->selectRaw('SUM(duration) AS minutes, SUM(xp_earned) AS xp')
                ->first();

            $minutesData[] = (int) ($pomo->minutes ?? 0);
            $xpData[]      = (int) ($pomo->xp      ?? 0);
        }

        // Streak growth: count current streak
        $streak = $this->calculateStreak($user);

        return [
            'labels'    => $labels,
            'tasks'     => $tasksData,
            'minutes'   => $minutesData,
            'xp'        => $xpData,
            'streak'    => $streak,
        ];
    }

    // -------------------------------------------------------------------------
    // E. LOCAL INSIGHTS (no AI)
    // -------------------------------------------------------------------------

    public function generateStatisticsInsights(mixed $user): array
    {
        $insights = [];

        // --- Most productive day of week ---
        $dayData = PomodoroSession::query()
            ->where('user_id', $user->id)
            ->where('type', 'focus')
            ->where('completed', true)
            ->selectRaw("DAYOFWEEK(created_at) AS dow, COUNT(*) AS cnt")
            ->groupByRaw("DAYOFWEEK(created_at)")
            ->orderByDesc('cnt')
            ->first();

        if ($dayData) {
            $dayName    = Carbon::now()->startOfWeek()->addDays($dayData->dow - 2)->format('l');
            $insights[] = [
                'icon'    => '🗓️',
                'type'    => 'pattern',
                'message' => "You are most productive on {$dayName}s.",
            ];
        }

        // --- Weekly consistency ---
        $comparison = $this->getWeeklyComparison($user);
        $sessionsDelta = $comparison['pomodoro_sessions']['delta'] ?? 0;
        if ($sessionsDelta > 0) {
            $insights[] = [
                'icon'    => '📈',
                'type'    => 'positive',
                'message' => "Your focus sessions increased {$sessionsDelta}% compared to last week. Keep it up!",
            ];
        } elseif ($sessionsDelta < -10) {
            $insights[] = [
                'icon'    => '⚡',
                'type'    => 'warning',
                'message' => "Focus sessions dropped this week. Try scheduling a study block tomorrow.",
            ];
        }

        // --- Strongest subject ---
        $subjectAnalytics = $this->getDetailedSubjectAnalytics($user);
        if (!empty($subjectAnalytics['strongest'])) {
            $s          = $subjectAnalytics['strongest'];
            $insights[] = [
                'icon'    => '🏆',
                'type'    => 'positive',
                'message' => "{$s['name']} has your highest completion rate at {$s['completion_rate']}%.",
            ];
        }

        // --- Weakest subject ---
        if (!empty($subjectAnalytics['weakest']) && $subjectAnalytics['weakest']['completion_rate'] < 50) {
            $w          = $subjectAnalytics['weakest'];
            $insights[] = [
                'icon'    => '📚',
                'type'    => 'suggestion',
                'message' => "{$w['name']} needs attention — only {$w['completion_rate']}% of tasks completed.",
            ];
        }

        // --- Overdue tasks warning ---
        $taskStats = $this->getTaskStatistics($user);
        if ($taskStats['overdue'] > 0) {
            $insights[] = [
                'icon'    => '⚠️',
                'type'    => 'warning',
                'message' => "You have {$taskStats['overdue']} overdue task(s). Consider reviewing your deadlines.",
            ];
        }

        // --- High completion rate celebration ---
        if ($taskStats['completion_rate'] >= 80) {
            $insights[] = [
                'icon'    => '🎯',
                'type'    => 'positive',
                'message' => "Outstanding! Your overall task completion rate is {$taskStats['completion_rate']}%.",
            ];
        }

        // --- Streak insight ---
        $streak = $this->calculateStreak($user);
        if ($streak >= 7) {
            $insights[] = [
                'icon'    => '🔥',
                'type'    => 'positive',
                'message' => "You're on a {$streak}-day study streak! Incredible consistency.",
            ];
        }

        return $insights;
    }

    // =========================================================================
    // PRIVATE HELPERS
    // =========================================================================

    private function buildDelta(int $current, int $previous): array
    {
        $delta = $previous > 0
            ? round((($current - $previous) / $previous) * 100, 1)
            : ($current > 0 ? 100.0 : 0.0);

        return compact('current', 'previous', 'delta');
    }

    private function heatLevel(int $count, int $max): int
    {
        if ($count === 0 || $max === 0) return 0;
        $ratio = $count / $max;
        return match (true) {
            $ratio >= 0.75 => 4,
            $ratio >= 0.50 => 3,
            $ratio >= 0.25 => 2,
            default        => 1,
        };
    }

    private function calculateStreak(mixed $user): int
    {
        $dates = PomodoroSession::query()
            ->where('user_id', $user->id)
            ->where('type', 'focus')
            ->where('completed', true)
            ->selectRaw("DATE(created_at) AS day")
            ->groupByRaw("DATE(created_at)")
            ->orderByDesc('day')
            ->pluck('day')
            ->map(fn ($d) => Carbon::parse($d));

        $streak  = 0;
        $current = Carbon::today();

        foreach ($dates as $date) {
            if ($date->toDateString() === $current->toDateString()) {
                $streak++;
                $current->subDay();
            } elseif ($date->lt($current)) {
                break;
            }
        }

        return $streak;
    }
}
