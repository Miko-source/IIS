<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\UserRole;

use App\Models\Activity;

class ActivityPolicy
{

public function view(User $user, Activity $activity)
{
    // admin 
    if ($user->hasRoleOrHigher(UserRole::ADMIN)) {
        return true;
    }

    // user must be allowed to view the parent campaign
    if ($activity->step && $activity->step->campaign) {
        if (! $user->can('view', $activity->step->campaign)) {
            return false;
        }
        // if campaign is viewable, no further checks are needed
        if ($user->can('view', $activity->step->campaign)) {
            return true;
        }
    }

    $step = $activity->step;
    $campaign = $step->campaign;

    if (!$campaign) {
        return false;
    }

    // campaign manager
    if ($campaign->user_id === $user->id) {
        return true;
    }

    return false;
}

    public function assignWorker(User $user, Activity $activity): bool
    {
        $step = $activity->step;
        $campaign = $step?->campaign;

        // ADMIN → always allowed
        if ($user->hasRoleOrHigher(UserRole::ADMIN)) {
            return true;
        }

        // Campaign manager (campaign owner)
        if ($campaign && $campaign->user_id === $user->id) {
            return true;
        }

        // Step coordinator (step owner)
        if ($step && $step->user_id === $user->id) {
            return true;
        }

        return false;
    }

        public function manageWorkers(User $user, Activity $activity): bool
    {
        $step = $activity->step;
        $campaign = $step?->campaign;

        // ADMIN
        if ($user->hasRoleOrHigher(UserRole::ADMIN)) {
            return true;
        }

        // CAMPAIGN MANAGER
        if ($campaign && $campaign->user_id === $user->id) {
            return true;
        }

        // STEP COORDINATOR
        if ($user->hasRole(UserRole::COORDINATOR) && $step && $step->user_id === $user->id) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Activity $activity): bool
    {
        $step = $activity->step;
        $campaign = $step?->campaign;

        // ADMIN
        if ($user->hasRoleOrHigher(UserRole::ADMIN)) {
            return true;
        }

        // CAMPAIGN MANAGER
        if ($campaign && $campaign->user_id === $user->id) {
            return true;
        }

        // STEP COORDINATOR
        if ($step && $step->user_id === $user->id) {
            return true;
        }

        return false;
    }
    
}
