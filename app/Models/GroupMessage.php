<?php
// app/Models/GroupMessage.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupMessage extends Model
{
    protected $fillable = ['study_group_id', 'user_id', 'message'];

    public function user()       { return $this->belongsTo(User::class); }
    public function studyGroup() { return $this->belongsTo(StudyGroup::class); }
}
