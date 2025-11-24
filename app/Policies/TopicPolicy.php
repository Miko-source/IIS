<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Topic;
use App\Models\User;

class TopicPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRoleOrHigher(UserRole::WORKER);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Topic $topic): bool
    {
        // Admin vidí vše
        if ($user->hasRoleOrHigher(UserRole::ADMIN)) {
            return true;
        }
        //1. správce kampaně jaekékoliv kampaně tématu
        //2. pracovník jakékoliv kampaně tématu
        //3. koordinátor kroku jakékoliv kampaně tématu
        return $topic->campaigns()
            ->where(function ($campaignQuery) use ($user) {
                $campaignQuery->where('campaigns.user_id', $user->id)
                    ->orWhereHas('users', function ($userQuery) use ($user) {
                        $userQuery->where('campaign_user.user_id', $user->id);
                    })
                    ->orWhereHas('steps', function ($stepQuery) use ($user) {
                        $stepQuery->where('user_id', $user->id);
                    });
            })
            ->exists();
    }

        // crud metody

        public function create(User $user): bool
            {
                return $user->hasRoleOrHigher(UserRole::ADMIN);
            }

            public function update(User $user, Topic $topic): bool
            {
                return $user->hasRoleOrHigher(UserRole::ADMIN);
            }

            public function delete(User $user, Topic $topic): bool
            {
                return $user->hasRoleOrHigher(UserRole::ADMIN);
            }

            public function restore(User $user, Topic $topic): bool
            {
                return false;
            }

            public function forceDelete(User $user, Topic $topic): bool
            {
                return false;
            }

}