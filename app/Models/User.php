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
            'password' => 'hashed',
        ];
    }

    // ── Relationships ─────────────────────────────────────────
    public function subjects()      { return $this->hasMany(Subject::class); }
    public function tasks()         { return $this->hasMany(Task::class); }
    public function notes()         { return $this->hasMany(Note::class); }
    public function pomodoroSessions() { return $this->hasMany(PomodoroSession::class); }
    public function streak()        { return $this->hasOne(Streak::class); }
    public function flashcards()    { return $this->hasMany(Flashcard::class); }
    public function exams()         { return $this->hasMany(Exam::class); }
    public function badges()        { return $this->belongsToMany(Badge::class, 'user_badges')->withPivot('earned_at'); }

    // ── Gamification helpers ───────────────────────────────────

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

    public function xpProgress(): int
    {
        // XP accumulated toward the next level (reset each level)
        $xpThisLevel = $this->xpForLevel($this->level);
        $xpNextLevel = $this->xpForNextLevel();
        $earned = $this->xp - $xpThisLevel;
        $needed  = $xpNextLevel - $xpThisLevel;
        return $needed > 0 ? max(0, (int) (($earned / $needed) * 100)) : 100;
    }

    public function addXp(int $amount): void
    {
        $this->xp += $amount;

        // Level up while XP exceeds threshold
        while ($this->xp >= $this->xpForNextLevel()) {
            $this->level++;
        }

        $this->save();
    }

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
}
