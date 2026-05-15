<?php
// app/Models/Exam.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','subject_id','title','notes','exam_date','location'];

    protected $casts = ['exam_date' => 'datetime'];

    public function user()    { return $this->belongsTo(User::class); }
    public function subject() { return $this->belongsTo(Subject::class); }

    public function daysUntil(): int
    {
        return max(0, (int) now()->diffInDays($this->exam_date, false));
    }

    public function isPast(): bool
    {
        return $this->exam_date->isPast();
    }

    public function urgencyClass(): string
    {
        $days = $this->daysUntil();
        if ($this->isPast()) return 'secondary';
        if ($days <= 3)      return 'danger';
        if ($days <= 7)      return 'warning';
        return 'success';
    }
}
