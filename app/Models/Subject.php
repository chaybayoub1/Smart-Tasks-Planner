<?php
// app/Models/Subject.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'color', 'description'];

    public function user()         { return $this->belongsTo(User::class); }
    public function tasks()        { return $this->hasMany(Task::class); }
    public function notes()        { return $this->hasMany(Note::class); }
    public function flashcards()   { return $this->hasMany(Flashcard::class); }
    public function exams()        { return $this->hasMany(Exam::class); }
    public function pomodoroSessions() { return $this->hasMany(PomodoroSession::class); }
}
