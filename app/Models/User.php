<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'username', 'email', 'password',
        'avatar', 'bio', 'university', 'academic_level',
        'field_of_study', 'study_methods', 'study_goal',
        'theme', 'timezone', 'language',
        'xp', 'level',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
<<<<<<< HEAD
        ];
    }

    // ── Relationships ─────────────────────────────────────────────────────────
=======
            'study_methods'     => 'array',
            'study_goal'        => 'float',
        ];
    }

    // ── Relationships ─────────────────────────────────────────
>>>>>>> hiba
    public function subjects()         { return $this->hasMany(Subject::class); }
    public function tasks()            { return $this->hasMany(Task::class); }
    public function notes()            { return $this->hasMany(Note::class); }
    public function pomodoroSessions() { return $this->hasMany(PomodoroSession::class); }
    public function streak()           { return $this->hasOne(Streak::class); }
    public function flashcards()       { return $this->hasMany(Flashcard::class); }
    public function exams()            { return $this->hasMany(Exam::class); }
    public function badges()           { return $this->belongsToMany(Badge::class, 'user_badges')->withPivot('earned_at'); }
<<<<<<< HEAD

    // ── Gamification helpers ──────────────────────────────────────────────────

    /**
     * XP required to reach a given level: 100 * level^1.5
     */
=======

    // Collaboration
    public function ownedGroups()  { return $this->hasMany(StudyGroup::class, 'owner_id'); }
    public function studyGroups()  { return $this->belongsToMany(StudyGroup::class, 'study_group_members')->withPivot('role', 'joined_at'); }
    public function groupMessages(){ return $this->hasMany(GroupMessage::class); }

    // ── Avatar helper ─────────────────────────────────────────
    public function avatarUrl(): string
    {
        if ($this->avatar) {
            return \Illuminate\Support\Facades\Storage::url($this->avatar);
        }
        return '';
    }

    public function initials(): string
    {
        $words = explode(' ', trim($this->name));
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($this->name, 0, 2));
    }

    // ── Gamification helpers ───────────────────────────────────
>>>>>>> hiba
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
<<<<<<< HEAD
        $earned      = $this->xp - $xpThisLevel;
        $needed      = $xpNextLevel - $xpThisLevel;

=======
        $earned = $this->xp - $xpThisLevel;
        $needed = $xpNextLevel - $xpThisLevel;
>>>>>>> hiba
        return $needed > 0 ? max(0, (int) (($earned / $needed) * 100)) : 100;
    }

    public function addXp(int $amount): void
    {
        $this->xp += $amount;
<<<<<<< HEAD

=======
>>>>>>> hiba
        while ($this->xp >= $this->xpForNextLevel()) {
            $this->level++;
        }
        $this->save();
    }

<<<<<<< HEAD
    // ── NOTE ─────────────────────────────────────────────────────────────────
    // totalStudyMinutes() and weeklyStudyMinutes() have been removed.
    // All study/task aggregation is now handled by ProductivityService,
    // which avoids per-user N+1 queries and keeps the model free of
    // business logic. Update any Blade views that called these methods
    // to use the $studyStats array passed from the controller instead:
    //
    //   Old: $user->totalStudyMinutes()      → $studyStats['total_study_minutes']
    //   Old: $user->weeklyStudyMinutes()     → use getWeeklyTasksChart() or getStudyStatistics()
=======
    // ── Computed stats ─────────────────────────────────────────
    public function totalStudyMinutes(): int
    {
        return (int) $this->pomodoroSessions()
            ->where('completed', true)
            ->where('type', 'focus')
            ->sum('duration');
    }

    public function weeklyStudyMinutes(): array
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $mins = (int) $this->pomodoroSessions()
                ->where('completed', true)
                ->where('type', 'focus')
                ->whereDate('created_at', $date)
                ->sum('duration');
            $data[] = ['date' => $date, 'minutes' => $mins];
        }
        return $data;
    }
>>>>>>> hiba
}
