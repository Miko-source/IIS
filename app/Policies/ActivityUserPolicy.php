<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ActivityUser;
use App\Enums\UserRole;

class ActivityUserPolicy
{
    /**
     * Uživatel může potvrdit/odmítnout aktivitu?
     */
    public function manage(User $user, ActivityUser $activityUser): bool
    {
        $activity = $activityUser->activity;
        $step = $activity->step;
        $campaign = $step->campaign;

        // ADMIN – může vše
        if ($user->hasRole(UserRole::ADMIN)) {
            return true;
        }

        // SPRÁVCE KAMPANĚ – může aktivity své kampaně
        if ($user->hasRole(UserRole::CAMPAIGN_MANAGER)) {
            return $campaign->user_id === $user->id;
        }

        // KOORDINÁTOR – může aktivity kroků, které koordinuje
        if ($user->hasRole(UserRole::COORDINATOR)) {
            return $step->user_id === $user->id;
        }

        // WORKER – nikdy
        return false;
    }

    
}

