<?php
// app/Models/PomodoroSession.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PomodoroSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','subject_id','task_id','duration','type',
        'completed','xp_earned','started_at','ended_at'
    ];

    protected $casts = [
        'completed'  => 'boolean',
        'started_at' => 'datetime',
        'ended_at'   => 'datetime',
    ];

    public function user()    { return $this->belongsTo(User::class); }
    public function subject() { return $this->belongsTo(Subject::class); }
    public function task()    { return $this->belongsTo(Task::class); }
}
