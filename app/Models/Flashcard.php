<?php
// app/Models/Flashcard.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Flashcard extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','subject_id','question','answer',
        'difficulty','review_count','next_review_at'
    ];

    protected $casts = ['next_review_at' => 'datetime'];

    public function user()    { return $this->belongsTo(User::class); }
    public function subject() { return $this->belongsTo(Subject::class); }

    public function isDueForReview(): bool
    {
        return $this->next_review_at === null || $this->next_review_at->isPast();
    }

    /**
     * Simple spaced repetition: schedule next review based on difficulty.
     * easy: +4 days, medium: +2 days, hard: +1 day
     */
    public function scheduleNextReview(string $difficulty): void
    {
        $days = match($difficulty) {
            'easy'  => 4 * ($this->review_count + 1),
            'medium'=> 2 * ($this->review_count + 1),
            'hard'  => 1,
            default => 2,
        };

        $this->difficulty      = $difficulty;
        $this->review_count++;
        $this->next_review_at  = Carbon::now()->addDays($days);
        $this->save();
    }

    public function difficultyColor(): string
    {
        return match($this->difficulty) {
            'easy'  => 'success',
            'hard'  => 'danger',
            default => 'warning',
        };
    }
}
