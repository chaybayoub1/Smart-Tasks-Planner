<?php
// database/seeders/BadgeSeeder.php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            // Pomodoro sessions
            ['name' => 'First Focus',    'description' => 'Complete your first Pomodoro session', 'icon' => '🍅', 'condition_type' => 'sessions_count',  'condition_value' => 1],
            ['name' => 'Focus Rookie',   'description' => 'Complete 5 Pomodoro sessions',          'icon' => '⏱️', 'condition_type' => 'sessions_count',  'condition_value' => 5],
            ['name' => 'Focus Pro',      'description' => 'Complete 25 Pomodoro sessions',         'icon' => '🔥', 'condition_type' => 'sessions_count',  'condition_value' => 25],
            ['name' => 'Focus Master',   'description' => 'Complete 100 Pomodoro sessions',        'icon' => '💎', 'condition_type' => 'sessions_count',  'condition_value' => 100],
            // Streaks
            ['name' => '3-Day Streak',   'description' => 'Study 3 days in a row',                'icon' => '📅', 'condition_type' => 'streak_days',     'condition_value' => 3],
            ['name' => 'Week Warrior',   'description' => 'Study 7 days in a row',                'icon' => '🗓️', 'condition_type' => 'streak_days',     'condition_value' => 7],
            ['name' => 'Unstoppable',    'description' => 'Study 30 days in a row',               'icon' => '🚀', 'condition_type' => 'streak_days',     'condition_value' => 30],
            // Notes
            ['name' => 'Note Taker',     'description' => 'Create your first note',               'icon' => '📝', 'condition_type' => 'notes_count',     'condition_value' => 1],
            ['name' => 'Notebook',       'description' => 'Create 20 notes',                      'icon' => '📓', 'condition_type' => 'notes_count',     'condition_value' => 20],
            // Flashcards
            ['name' => 'Card Shark',     'description' => 'Create 10 flashcards',                 'icon' => '🃏', 'condition_type' => 'flashcards_count', 'condition_value' => 10],
            ['name' => 'Memory Master',  'description' => 'Create 50 flashcards',                 'icon' => '🧠', 'condition_type' => 'flashcards_count', 'condition_value' => 50],
            // Levels
            ['name' => 'Level 5',        'description' => 'Reach level 5',                        'icon' => '⭐', 'condition_type' => 'level',            'condition_value' => 5],
            ['name' => 'Level 10',       'description' => 'Reach level 10',                       'icon' => '🌟', 'condition_type' => 'level',            'condition_value' => 10],
            // Tasks
            ['name' => 'Task Crusher',   'description' => 'Complete 10 tasks',                    'icon' => '✅', 'condition_type' => 'tasks_completed',  'condition_value' => 10],
        ];

        foreach ($badges as $badge) {
            Badge::firstOrCreate(['name' => $badge['name']], $badge);
        }
    }
}
