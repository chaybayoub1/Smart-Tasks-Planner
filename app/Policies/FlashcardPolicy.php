<?php
// app/Policies/FlashcardPolicy.php
namespace App\Policies;
use App\Models\Flashcard;
use App\Models\User;

class FlashcardPolicy {
    public function update(User $user, Flashcard $f): bool { return $user->id === $f->user_id; }
    public function delete(User $user, Flashcard $f): bool { return $user->id === $f->user_id; }
}
