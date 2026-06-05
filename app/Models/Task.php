<?php
// app/Models/Task.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','group_id','assigned_to','subject_id','title','description',
        'due_date','priority','status'
    ];

    protected $casts = ['due_date' => 'date'];

    public function user()    { return $this->belongsTo(User::class); }
    public function subject() { return $this->belongsTo(Subject::class); }
    public function group()   { return $this->belongsTo(CollaborationGroup::class, 'group_id'); }
    public function assignee(){ return $this->belongsTo(User::class, 'assigned_to'); }
    public function pomodoroSessions() { return $this->hasMany(PomodoroSession::class); }
    public function completedPomodoroSessions()
    {
        return $this->pomodoroSessions()
            ->where('completed', true)
            ->where('type', 'focus');
    }

    public function completedPomodoroCount(): int
    {
        return (int) ($this->completed_pomodoro_sessions_count
            ?? $this->completedPomodoroSessions()->count());
    }

    public function studiedMinutes(): int
    {
        return (int) ($this->completed_pomodoro_sessions_sum_duration
            ?? $this->completedPomodoroSessions()->sum('duration'));
    }

    public function isOverdue(): bool
    {
        return $this->due_date->isPast() && $this->status !== 'completed';
    }

    public function priorityBadgeClass(): string
    {
        return match($this->priority) {
            'high'   => 'danger',
            'medium' => 'warning',
            default  => 'secondary',
        };
    }

    public function statusBadgeClass(): string
    {
        return match($this->status) {
            'completed'   => 'success',
            'in_progress' => 'primary',
            default       => 'secondary',
        };
    }
}
