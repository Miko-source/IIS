<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ActivityUser;
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

    // has to be able to see campaign
    if ($activity->step && $activity->step->campaign) {
        if (! $user->can('view', $activity->step->campaign)) {
            return false;
        }
        // if user can see campaign, no further checks needed
        if ($user->can('view', $activity->step->campaign)) {
            return true;
        }
    }

    // if step is missing, allow viewing
    if (!$activity->step) {
        return true;
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


    
}
