<?php
// app/Providers/AuthServiceProvider.php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Subject;    use App\Policies\SubjectPolicy;
use App\Models\Task;       use App\Policies\TaskPolicy;
use App\Models\Note;       use App\Policies\NotePolicy;
use App\Models\Flashcard;  use App\Policies\FlashcardPolicy;
use App\Models\Exam;       use App\Policies\ExamPolicy;
use App\Models\CollaborationGroup; use App\Policies\CollaborationGroupPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Subject::class   => SubjectPolicy::class,
        Task::class      => TaskPolicy::class,
        Note::class      => NotePolicy::class,
        Flashcard::class => FlashcardPolicy::class,
        Exam::class      => ExamPolicy::class,
        CollaborationGroup::class => CollaborationGroupPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
