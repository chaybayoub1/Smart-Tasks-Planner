<?php
// app/Policies/ExamPolicy.php
namespace App\Policies;
use App\Models\Exam;
use App\Models\User;

class ExamPolicy {
    public function update(User $user, Exam $exam): bool { return $user->id === $exam->user_id; }
    public function delete(User $user, Exam $exam): bool { return $user->id === $exam->user_id; }
}
