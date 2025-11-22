<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Topic;
use App\Models\User;

class TopicPolicy
{
    /**
     * Determine whether the user can view any models.
     */
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
        //1. campaign manager of any campaign of the topic
        //2. worker of any campaign of the topic
        //3. step coordinator of any campaign of the topic
        return $topic->campaigns()
            ->where(function ($campaignQuery) use ($user) {
                // campaign manager of campaign the topic
                $campaignQuery->where('campaigns.user_id', $user->id)
                    // worker of any campaign of the topic
                    ->orWhereHas('users', function ($userQuery) use ($user) {
                        $userQuery->where('campaign_user.user_id', $user->id);
                    })
                    // step coordinator of any campaign of the topic
                    ->orWhereHas('steps', function ($stepQuery) use ($user) {
                        $stepQuery->where('user_id', $user->id);
                    });
            })
            ->exists();
    }

        // crud methods are limited by Middleware, but to be sure

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