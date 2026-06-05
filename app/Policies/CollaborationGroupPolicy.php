<?php

namespace App\Policies;

use App\Models\CollaborationGroup;
use App\Models\User;

class CollaborationGroupPolicy
{
    public function view(User $user, CollaborationGroup $group): bool
    {
        return $group->hasMember($user);
    }

    public function update(User $user, CollaborationGroup $group): bool
    {
        return $group->isAdmin($user);
    }

    public function delete(User $user, CollaborationGroup $group): bool
    {
        return $group->isOwner($user);
    }

    public function manageMembers(User $user, CollaborationGroup $group): bool
    {
        return $group->isAdmin($user);
    }
}
