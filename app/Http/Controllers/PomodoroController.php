<?php
// app/Http/Controllers/PomodoroController.php

namespace App\Http\Controllers;

use App\Models\PomodoroSession;
use App\Services\GamificationService;
use Illuminate\Http\Request;

class PomodoroController extends Controller
{
    public function __construct(private GamificationService $gamification) {}

    public function index()
    {
        $user     = auth()->user();
        $subjects = $user->subjects()->get();

        $recentSessions = $user->pomodoroSessions()
            ->with('subject')
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        $todayMinutes = (int) $user->pomodoroSessions()
            ->where('completed', true)
            ->where('type', 'focus')
            ->whereDate('created_at', today())
            ->sum('duration');

        $totalSessions = $user->pomodoroSessions()->where('completed', true)->count();

        return view('pomodoro.index', compact('subjects', 'recentSessions', 'todayMinutes', 'totalSessions'));
    }

    /**
     * AJAX: Save a completed (or abandoned) session.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'subject_id'  => 'nullable|exists:subjects,id',
            'duration'    => 'required|integer|min:1',
            'type'        => 'required|in:focus,short_break,long_break',
            'completed'   => 'required|boolean',
            'started_at'  => 'nullable|date',
        ]);

        $user = auth()->user();
        $xp   = 0;

        if ($data['completed'] && $data['type'] === 'focus') {
            // 10 XP per 25-minute session, scaled
            $xp = (int) round(($data['duration'] / 25) * 10);
        }

        $session = $user->pomodoroSessions()->create([
            ...$data,
            'xp_earned'  => $xp,
            'started_at' => $data['started_at'] ?? now()->subMinutes($data['duration']),
            'ended_at'   => now(),
        ]);

        if ($data['completed'] && $data['type'] === 'focus') {
            // Add XP & level up
            $user->addXp($xp);

            // Update streak
            $streak = $user->streak ?? $user->streak()->create([
                'current_streak' => 0, 'longest_streak' => 0, 'last_activity_date' => null,
            ]);
            $streak->recordActivity();

            // Check badges
            $this->gamification->checkAndAwardBadges($user);
        }

        return response()->json([
            'success'     => true,
            'xp_earned'   => $xp,
            'total_xp'    => $user->fresh()->xp,
            'level'       => $user->fresh()->level,
            'session_id'  => $session->id,
        ]);
    }
}
