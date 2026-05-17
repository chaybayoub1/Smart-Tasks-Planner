<?php
// app/Services/InsightService.php

namespace App\Services;

class InsightService
{
    // -------------------------------------------------------------------------
    // Priority-ordered rules.
    // The first rule whose condition is satisfied wins; the rest are skipped.
    // Each rule returns:
    //   message  – the insight text shown in the widget
    //   emoji    – leading icon (rendered before the message)
    //   tone     – 'positive' | 'warning' | 'neutral'  (drives widget colour)
    // -------------------------------------------------------------------------

    /**
     * Generate a single Smart Insight for the current user.
     *
     * @param  array  $taskStats   Output of ProductivityService::getTaskStatistics()
     * @param  array  $studyStats  Output of ProductivityService::getStudyStatistics()
     * @param  mixed  $streak      The user's streak Eloquent model (or null)
     * @return array{ message: string, emoji: string, tone: string }
     */
    public function generate(array $taskStats, array $studyStats, mixed $streak): array
    {
        $completionRate  = (float) ($taskStats['completion_rate']  ?? 0);
        $overdueCount    = (int)   ($taskStats['overdue']          ?? 0);
        $overdueRate     = (float) ($taskStats['overdue_percentage'] ?? $taskStats['overdue_rate'] ?? 0);
        $totalTasks      = (int)   ($taskStats['total']            ?? 0);
        $currentStreak   = (int)   ($streak->current_streak        ?? 0);
        $weeklyXp        = (int)   ($studyStats['weekly_xp']       ?? 0);
        $totalXp         = (int)   ($studyStats['total_xp']        ?? 0);
        $weeklyMins      = (int)   ($studyStats['weekly_study_minutes'] ?? 0);
        $weeklySessions  = (int)   ($studyStats['weekly_sessions'] ?? 0);
        $avgSession      = (float) ($studyStats['avg_session_minutes'] ?? 0);

        // ── Rule 1: Critical overdue load ────────────────────────────────────
        if ($overdueCount > 5 || $overdueRate > 30) {
            return $this->insight(
                '⚠️',
                "{$overdueCount} overdue tasks need your attention. Tackle the smallest one first.",
                'warning'
            );
        }

        // ── Rule 2: Light overdue warning ────────────────────────────────────
        if ($overdueCount >= 2) {
            return $this->insight(
                '📌',
                "You have {$overdueCount} overdue tasks. A quick review now saves stress later.",
                'warning'
            );
        }

        // ── Rule 3: Excellent completion rate ────────────────────────────────
        if ($completionRate >= 85 && $totalTasks >= 3) {
            return $this->insight(
                '🏆',
                "Outstanding! {$completionRate}% completion rate — you're in peak productivity mode.",
                'positive'
            );
        }

        // ── Rule 4: Good completion rate ─────────────────────────────────────
        if ($completionRate >= 70 && $totalTasks >= 3) {
            return $this->insight(
                '✅',
                "Solid work — {$completionRate}% of your tasks are done. Keep the momentum!",
                'positive'
            );
        }

        // ── Rule 5: Long streak ───────────────────────────────────────────────
        if ($currentStreak >= 10) {
            return $this->insight(
                '🔥',
                "{$currentStreak}-day streak! Your consistency is building real academic momentum.",
                'positive'
            );
        }

        // ── Rule 6: Good streak ───────────────────────────────────────────────
        if ($currentStreak >= 5) {
            return $this->insight(
                '🔥',
                "{$currentStreak} days in a row — great consistency! Don't break the chain.",
                'positive'
            );
        }

        // ── Rule 7: Strong weekly XP ─────────────────────────────────────────
        if ($weeklyXp >= 200) {
            return $this->insight(
                '⚡',
                "You've earned {$weeklyXp} XP this week — one of your best performances yet!",
                'positive'
            );
        }

        // ── Rule 8: Good study session volume ────────────────────────────────
        if ($weeklySessions >= 5) {
            return $this->insight(
                '📚',
                "{$weeklySessions} focus sessions this week — your study habits are solid.",
                'positive'
            );
        }

        // ── Rule 9: Strong average session length ────────────────────────────
        if ($avgSession >= 45) {
            return $this->insight(
                '🧠',
                "Your average session is " . round($avgSession) . " min — deep focus pays off.",
                'positive'
            );
        }

        // ── Rule 10: Good weekly study time ──────────────────────────────────
        if ($weeklyMins >= 120) {
            $hours = floor($weeklyMins / 60);
            $mins  = $weeklyMins % 60;
            $label = $hours > 0 ? "{$hours}h {$mins}m" : "{$mins}m";
            return $this->insight(
                '⏱️',
                "You've studied {$label} this week. Steady effort leads to steady progress.",
                'positive'
            );
        }

        // ── Rule 11: No tasks yet ─────────────────────────────────────────────
        if ($totalTasks === 0) {
            return $this->insight(
                '🚀',
                "Add your first task to get started — even one small step builds momentum.",
                'neutral'
            );
        }

        // ── Rule 12: Low completion, nudge ───────────────────────────────────
        if ($completionRate < 40 && $totalTasks >= 3) {
            return $this->insight(
                '💪',
                "Pick one task to complete today — small wins build big habits.",
                'neutral'
            );
        }

        // ── Rule 13: Default motivational fallback ────────────────────────────
        return $this->insight(
            '💡',
            "Every session counts. Open your planner and make today productive.",
            'neutral'
        );
    }

    // -------------------------------------------------------------------------
    // Private helper — builds the return shape consistently
    // -------------------------------------------------------------------------

    /**
     * @return array{ message: string, emoji: string, tone: string }
     */
    private function insight(string $emoji, string $message, string $tone): array
    {
        return compact('emoji', 'message', 'tone');
    }
}
