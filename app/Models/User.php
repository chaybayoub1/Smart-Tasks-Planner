<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

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

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'study_methods' => 'array',
            'study_goal' => 'float',
        ];
    }

    // ── Relationships ─────────────────────────────────────────

    public function subjects() { return $this->hasMany(Subject::class); }
    public function tasks() { return $this->hasMany(Task::class); }
    public function notes() { return $this->hasMany(Note::class); }
    public function pomodoroSessions() { return $this->hasMany(PomodoroSession::class); }
    public function streak() { return $this->hasOne(Streak::class); }
    public function flashcards() { return $this->hasMany(Flashcard::class); }
    public function exams() { return $this->hasMany(Exam::class); }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges')
            ->withPivot('earned_at');
    }

    // ── Collaboration ─────────────────────────────────────────

    public function ownedGroups()
    {
        return $this->hasMany(CollaborationGroup::class, 'owner_id');
    }

    public function collaborationGroups()
    {
        return $this->belongsToMany(
            CollaborationGroup::class,
            'collaboration_group_members',
            'user_id',
            'group_id'
        )->withPivot('role', 'joined_at')->withTimestamps();
    }

    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    // ── Avatar helper ─────────────────────────────────────────

    public function avatarUrl(): string
    {
        return $this->avatar ? Storage::url($this->avatar) : '';
    }

    public function initials(): string
    {
        $words = explode(' ', trim($this->name));

        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }

        return strtoupper(substr($this->name, 0, 2));
    }

    // ── Gamification ──────────────────────────────────────────

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
        $xpThisLevel = $this->xpForLevel($this->level);
        $xpNextLevel = $this->xpForNextLevel();

        $earned = $this->xp - $xpThisLevel;
        $needed = $xpNextLevel - $xpThisLevel;

        return $needed > 0
            ? max(0, (int) (($earned / $needed) * 100))
            : 100;
    }

    public function addXp(int $amount): void
    {
        $this->xp += $amount;

        while ($this->xp >= $this->xpForNextLevel()) {
            $this->level++;
        }

        $this->save();
    }
}