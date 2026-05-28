<?php
// app/Models/StudyGroup.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class StudyGroup extends Model
{
    protected $fillable = [
        'name', 'description', 'subject', 'invite_code',
        'owner_id', 'is_public', 'max_members',
    ];

    protected $casts = ['is_public' => 'boolean'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($group) {
            if (empty($group->invite_code)) {
                do {
                    $code = strtoupper(Str::random(6));
                } while (self::where('invite_code', $code)->exists());
                $group->invite_code = $code;
            }
        });
    }

    public function owner()   { return $this->belongsTo(User::class, 'owner_id'); }
    public function members() { return $this->belongsToMany(User::class, 'study_group_members')->withPivot('role', 'joined_at'); }
    public function messages(){ return $this->hasMany(GroupMessage::class)->latest(); }
    public function groupTasks(){ return $this->hasMany(GroupTask::class); }

    public function isMember(User $user): bool
    {
        return $this->members()->where('user_id', $user->id)->exists();
    }

    public function memberCount(): int
    {
        return $this->members()->count();
    }
}
