<?php

namespace Database\Factories;

use App\Models\PomodoroSession;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class PomodoroSessionFactory extends Factory
{
    protected $model = PomodoroSession::class;

    public function definition(): array
    {
        // Spread sessions across the last 60 days for realistic heatmap data
        $startedAt = Carbon::now()->subDays(fake()->numberBetween(0, 60))
                           ->setHour(fake()->numberBetween(7, 22))
                           ->setMinute(0);

        $duration = fake()->randomElement([25, 25, 25, 50, 50]); // weighted toward 25 min

        return [
            'user_id'    => User::factory(),
            'subject_id' => null, // Override in seeder
            'duration'   => $duration,
            'type'       => 'focus',
            'completed'  => fake()->boolean(85), // 85% completion rate
            'xp_earned'  => fn (array $attrs) => $attrs['completed'] ? $duration * 2 : 0,
            'started_at' => $startedAt,
            'ended_at'   => (clone $startedAt)->addMinutes($duration),
            'created_at' => $startedAt, // Important: heatmap uses created_at
            'updated_at' => $startedAt,
        ];
    }

    /**
     * Mark as completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attrs) => [
            'completed' => true,
            'xp_earned' => $attrs['duration'] * 2,
        ]);
    }

    /**
     * Assign a random subject from the user's subjects.
     */
    public function forSubject(Subject $subject): static
    {
        return $this->state(['subject_id' => $subject->id]);
    }
}
