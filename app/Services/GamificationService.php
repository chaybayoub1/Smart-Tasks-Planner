<?php
// app/Services/GamificationService.php

namespace App\Services;

use App\Models\Badge;
use App\Models\User;

class GamificationService
{
    /**
     * Check all badge conditions and award any not yet earned.
     */
    public function checkAndAwardBadges(User $user): void
    {
        $earnedIds  = $user->badges()->pluck('badge_id')->toArray();
        $allBadges  = Badge::all();

        foreach ($allBadges as $badge) {
            if (in_array($badge->id, $earnedIds)) {
                continue;
            }

            if ($this->conditionMet($user, $badge->condition_type, $badge->condition_value)) {
                $user->badges()->attach($badge->id, ['earned_at' => now()]);
            }
        }
    }

    private function conditionMet(User $user, string $type, int $value): bool
    {
        return match($type) {
            'sessions_count' => $user->pomodoroSessions()->where('completed', true)->where('type', 'focus')->count() >= $value,
            'streak_days'    => ($user->streak?->current_streak ?? 0) >= $value,
            'notes_count'    => $user->notes()->count() >= $value,
            'flashcards_count' => $user->flashcards()->count() >= $value,
            'level'          => $user->level >= $value,
            'tasks_completed'=> $user->tasks()->where('status', 'completed')->count() >= $value,
            default          => false,
        };
    }
}
