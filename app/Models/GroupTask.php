<?php
// app/Models/GroupTask.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupTask extends Model
{
    protected $fillable = [
        'study_group_id', 'created_by', 'assigned_to',
        'title', 'description', 'due_date', 'status', 'priority',
    ];

    protected $casts = ['due_date' => 'date'];

    public function studyGroup()  { return $this->belongsTo(StudyGroup::class); }
    public function creator()     { return $this->belongsTo(User::class, 'created_by'); }
    public function assignee()    { return $this->belongsTo(User::class, 'assigned_to'); }

    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast() && $this->status !== 'completed';
    }
}
