<?php
// app/Models/Task.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','subject_id','title','description',
        'due_date','duration','priority','status'
    ];

    protected $casts = ['due_date' => 'date'];

    public function user()    { return $this->belongsTo(User::class); }
    public function subject() { return $this->belongsTo(Subject::class); }

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
