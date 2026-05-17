<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'xp', 'level'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ── Relationships ─────────────────────────────────────────────────────────
    public function subjects()         { return $this->hasMany(Subject::class); }
    public function tasks()            { return $this->hasMany(Task::class); }
    public function notes()            { return $this->hasMany(Note::class); }
    public function pomodoroSessions() { return $this->hasMany(PomodoroSession::class); }
    public function streak()           { return $this->hasOne(Streak::class); }
    public function flashcards()       { return $this->hasMany(Flashcard::class); }
    public function exams()            { return $this->hasMany(Exam::class); }
    public function badges()           { return $this->belongsToMany(Badge::class, 'user_badges')->withPivot('earned_at'); }

    // ── Gamification helpers ──────────────────────────────────────────────────

    /**
     * XP required to reach a given level: 100 * level^1.5
     */
    public function xpForLevel(int $level): int
    {
        return (int) (100 * pow($level, 1.5));
    }

    public function xpForNextLevel(): int
    {
        return $this->xpForLevel($this->level + 1);
    }

    /**
     * Progress percentage (0–100) toward the next level.
     */
    public function xpProgress(): int
    {
        $xpThisLevel = $this->xpForLevel($this->level);
        $xpNextLevel = $this->xpForNextLevel();
        $earned      = $this->xp - $xpThisLevel;
        $needed      = $xpNextLevel - $xpThisLevel;

        return $needed > 0 ? max(0, (int) (($earned / $needed) * 100)) : 100;
    }

    public function addXp(int $amount): void
    {
        $this->xp += $amount;

        while ($this->xp >= $this->xpForNextLevel()) {
            $this->level++;
        }

        $this->save();
    }

    // ── NOTE ─────────────────────────────────────────────────────────────────
    // totalStudyMinutes() and weeklyStudyMinutes() have been removed.
    // All study/task aggregation is now handled by ProductivityService,
    // which avoids per-user N+1 queries and keeps the model free of
    // business logic. Update any Blade views that called these methods
    // to use the $studyStats array passed from the controller instead:
    //
    //   Old: $user->totalStudyMinutes()      → $studyStats['total_study_minutes']
    //   Old: $user->weeklyStudyMinutes()     → use getWeeklyTasksChart() or getStudyStatistics()
}
