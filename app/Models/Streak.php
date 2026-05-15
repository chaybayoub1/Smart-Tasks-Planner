<?php
// app/Models/Streak.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Streak extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','current_streak','longest_streak','last_activity_date'];

    protected $casts = ['last_activity_date' => 'date'];

    public function user() { return $this->belongsTo(User::class); }

    /**
     * Record activity for today. Increments or resets streak.
     */
    public function recordActivity(): void
    {
        $today = now()->toDateString();

        if ($this->last_activity_date?->toDateString() === $today) {
            return; // already counted today
        }

        $yesterday = now()->subDay()->toDateString();

        if ($this->last_activity_date?->toDateString() === $yesterday) {
            $this->current_streak++;
        } else {
            $this->current_streak = 1; // reset
        }

        if ($this->current_streak > $this->longest_streak) {
            $this->longest_streak = $this->current_streak;
        }

        $this->last_activity_date = $today;
        $this->save();
    }
}
