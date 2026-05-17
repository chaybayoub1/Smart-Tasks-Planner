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

    /**
     * Returns a complete snapshot of the user's task metrics.
     */
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

    /**
     * Returns study-time and XP aggregates for the current week and all-time.
     */
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
    // 3. WEEKLY TASKS CHART  (last 7 days, Chart.js-ready)
    // -------------------------------------------------------------------------

    /**
     * Returns completed-tasks count for each of the last 7 days.
     *
     * @return array{labels: string[], data: int[]}
     */
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
    // 4. XP PROGRESS CHART  (last 30 days, cumulative, Chart.js-ready)
    // -------------------------------------------------------------------------

    /**
     * Returns cumulative XP earned per day over the last 30 days.
     *
     * @return array{labels: string[], data: int[]}
     */
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
    // 5. HEATMAP DATA  (last 365 days, GitHub-style)
    // -------------------------------------------------------------------------

    /**
     * Returns daily activity counts for the last 365 days.
     *
     * @return array<string, int>
     */
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

    /**
     * Compares current week vs previous week for key metrics.
     */
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

    /**
     * Returns per-subject breakdown: completed tasks + study minutes.
     * Ready for Chart.js radar / bar chart.
     *
     * @return array{labels: string[], tasks: int[], minutes: int[]}
     */
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
    // 8. SUBJECT DISTRIBUTION  ← NEW METHOD (fixes the undefined variable error)
    // -------------------------------------------------------------------------

    /**
     * Returns task distribution by subject — Chart.js donut-ready.
     *
     * Single JOIN query, no N+1. Ordered by task count DESC so the first
     * entry is always the most active subject (used for the 🏆 badge).
     *
     * @return array{
     *   labels:      string[],
     *   data:        int[],
     *   percentages: float[],
     *   colors:      string[],
     *   top_subject: string|null,
     *   total:       int
     * }
     */
    public function getSubjectDistribution(mixed $user): array
    {
        // 10-color SaaS palette — cycles for users with more than 10 subjects
        $palette = [
            '#6366f1', // indigo
            '#10b981', // emerald
            '#f59e0b', // amber
            '#0ea5e9', // sky
            '#f43f5e', // rose
            '#8b5cf6', // violet
            '#06b6d4', // cyan
            '#84cc16', // lime
            '#fb923c', // orange
            '#ec4899', // pink
        ];

        // Single query: count ALL tasks per subject (any status) for this user
        $rows = Task::query()
            ->where('tasks.user_id', $user->id)
            ->whereNotNull('tasks.subject_id')
            ->join('subjects', 'subjects.id', '=', 'tasks.subject_id')
            ->selectRaw('subjects.name AS subject_name, COUNT(*) AS total')
            ->groupBy('subjects.id', 'subjects.name')
            ->orderByDesc('total')
            ->get();

        // Return a safe empty structure when the user has no subject-linked tasks
        if ($rows->isEmpty()) {
            return [
                'labels'      => [],
                'data'        => [],
                'percentages' => [],
                'colors'      => [],
                'top_subject' => null,
                'total'       => 0,
            ];
        }

        $grandTotal  = $rows->sum('total');
        $labels      = [];
        $data        = [];
        $percentages = [];
        $colors      = [];

        foreach ($rows as $i => $row) {
            $labels[]      = $row->subject_name;
            $data[]        = (int) $row->total;
            $percentages[] = $grandTotal > 0
                ? round(($row->total / $grandTotal) * 100, 1)
                : 0.0;
            $colors[]      = $palette[$i % count($palette)];
        }

        return [
            'labels'      => $labels,
            'data'        => $data,
            'percentages' => $percentages,
            'colors'      => $colors,
            'top_subject' => $labels[0] ?? null,  // highest count (ORDER BY DESC)
            'total'       => (int) $grandTotal,
        ];
    }

    // -------------------------------------------------------------------------
    // PRIVATE HELPERS
    // -------------------------------------------------------------------------

    /**
     * Builds a delta comparison array between two integer values.
     *
     * @return array{current: int, previous: int, delta: float}
     */
    private function buildDelta(int $current, int $previous): array
    {
        $delta = $previous > 0
            ? round((($current - $previous) / $previous) * 100, 1)
            : ($current > 0 ? 100.0 : 0.0);

        return compact('current', 'previous', 'delta');
    }
}