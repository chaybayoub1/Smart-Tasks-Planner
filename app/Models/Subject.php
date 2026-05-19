<?php
// app/Models/Subject.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'color', 'description'];

    // -------------------------------------------------------------------------
    // RELATIONSHIPS
    // -------------------------------------------------------------------------

    public function user()             { return $this->belongsTo(User::class); }
    public function tasks()            { return $this->hasMany(Task::class); }
    public function notes()            { return $this->hasMany(Note::class); }
    public function flashcards()       { return $this->hasMany(Flashcard::class); }
    public function exams()            { return $this->hasMany(Exam::class); }
    public function pomodoroSessions() { return $this->hasMany(PomodoroSession::class); }

    // -------------------------------------------------------------------------
    // ANALYTICS HELPERS — used by ProductivityService & views
    // -------------------------------------------------------------------------

    /**
     * Total completed tasks for this subject.
     */
    public function completedTasksCount(): int
    {
        return $this->tasks()->where('status', 'completed')->count();
    }

    /**
     * Task completion rate (0–100).
     */
    public function completionRate(): float
    {
        $total = $this->tasks()->count();
        if ($total === 0) return 0.0;
        return round(($this->completedTasksCount() / $total) * 100, 1);
    }

    /**
     * Total focus minutes studied for this subject.
     */
    public function totalStudyMinutes(): int
    {
        return (int) $this->pomodoroSessions()
            ->where('type', 'focus')
            ->where('completed', true)
            ->sum('duration');
    }

    /**
     * Total focus sessions for this subject.
     */
    public function totalFocusSessions(): int
    {
        return $this->pomodoroSessions()
            ->where('type', 'focus')
            ->where('completed', true)
            ->count();
    }

    /**
     * Formatted study time (e.g. "2h 35m").
     */
    public function formattedStudyTime(): string
    {
        $minutes = $this->totalStudyMinutes();
        $hours   = intdiv($minutes, 60);
        $mins    = $minutes % 60;
        return $hours > 0 ? "{$hours}h {$mins}m" : "{$mins}m";
    }
}
