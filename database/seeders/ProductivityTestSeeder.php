<?php

namespace Database\Seeders;

use App\Models\PomodoroSession;
use App\Models\Subject;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ProductivityTestSeeder extends Seeder
{
    /**
     * Seeds realistic productivity data for the first user (or a specific one).
     *
     * Usage:
     *   php artisan db:seed --class=ProductivityTestSeeder
     *
     * To target a specific user, set SEED_USER_ID in your .env or pass it inline:
     *   SEED_USER_ID=2 php artisan db:seed --class=ProductivityTestSeeder
     */
    public function run(): void
    {
        $userId = (int) env('SEED_USER_ID', 0);

        $user = $userId > 0
            ? User::findOrFail($userId)
            : User::first();

        if (! $user) {
            $this->command->error('No user found. Create a user account first.');
            return;
        }

        $this->command->info("Seeding productivity data for user: {$user->name} (ID: {$user->id})");

        // ---------------------------------------------------------------
        // 1. Create subjects if none exist
        // ---------------------------------------------------------------
        $subjectDefs = [
            ['name' => 'Mathematics',  'color' => '#6366f1'],
            ['name' => 'Physics',      'color' => '#10b981'],
            ['name' => 'Programming',  'color' => '#f59e0b'],
            ['name' => 'Literature',   'color' => '#f43f5e'],
            ['name' => 'History',      'color' => '#0ea5e9'],
        ];

        $subjects = [];
        foreach ($subjectDefs as $def) {
            $subjects[] = Subject::firstOrCreate(
                ['user_id' => $user->id, 'name' => $def['name']],
                ['color' => $def['color'], 'description' => "Study notes for {$def['name']}"]
            );
        }

        $this->command->info('Subjects ready: ' . count($subjects));

        // ---------------------------------------------------------------
        // 2. Create tasks spread across subjects and dates
        // ---------------------------------------------------------------
        $taskStatuses   = ['completed', 'completed', 'completed', 'pending', 'in_progress'];
        $taskPriorities = ['high', 'medium', 'low'];
        $tasksCreated   = 0;

        foreach ($subjects as $subject) {
            // 8–15 tasks per subject
            $count = rand(8, 15);
            for ($i = 0; $i < $count; $i++) {
                $status  = $taskStatuses[array_rand($taskStatuses)];
                $daysAgo = rand(0, 60);
                $created = Carbon::now()->subDays($daysAgo);
                $updated = $status === 'completed'
                    ? Carbon::now()->subDays(rand(0, $daysAgo))
                    : $created;

                Task::create([
                    'user_id'     => $user->id,
                    'subject_id'  => $subject->id,
                    'title'       => "Task {$i}: Study {$subject->name} topic " . rand(1, 20),
                    'description' => "Study session task for {$subject->name}",
                    'due_date'    => Carbon::now()->addDays(rand(-10, 30)),
                    'duration'    => rand(30, 120),
                    'priority'    => $taskPriorities[array_rand($taskPriorities)],
                    'status'      => $status,
                    'created_at'  => $created,
                    'updated_at'  => $updated,
                ]);
                $tasksCreated++;
            }
        }

        $this->command->info("Tasks created: {$tasksCreated}");

        // ---------------------------------------------------------------
        // 3. Create Pomodoro sessions spread across subjects and dates
        // ---------------------------------------------------------------
        $sessionDurations = [25, 25, 25, 50]; // weighted toward 25 min
        $sessionsCreated  = 0;

        // Vary session density by subject to create "strongest/weakest" contrast
        $sessionCounts = [40, 30, 55, 15, 20]; // Mathematics, Physics, Programming, Literature, History

        foreach ($subjects as $index => $subject) {
            $count = $sessionCounts[$index] ?? 20;
            for ($i = 0; $i < $count; $i++) {
                $daysAgo   = rand(0, 60);
                $hour      = rand(7, 22);
                $duration  = $sessionDurations[array_rand($sessionDurations)];
                $completed = (rand(1, 100) <= 85); // 85% completion rate
                $startedAt = Carbon::now()
                    ->subDays($daysAgo)
                    ->setHour($hour)
                    ->setMinute(0)
                    ->setSecond(0);

                PomodoroSession::create([
                    'user_id'    => $user->id,
                    'subject_id' => $subject->id,
                    'duration'   => $duration,
                    'type'       => 'focus',
                    'completed'  => $completed,
                    'xp_earned'  => $completed ? $duration * 2 : 0,
                    'started_at' => $startedAt,
                    'ended_at'   => (clone $startedAt)->addMinutes($duration),
                    'created_at' => $startedAt,
                    'updated_at' => $startedAt,
                ]);
                $sessionsCreated++;
            }
        }

        $this->command->info("Pomodoro sessions created: {$sessionsCreated}");
        $this->command->info('');
        $this->command->info('✅ Seeding complete! Visit /statistics to see live analytics.');
        $this->command->info('   Heatmap, subject analytics, focus charts should now show real data.');
    }
}
