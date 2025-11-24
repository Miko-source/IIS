<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\UserRole;

use App\Models\Activity;

class ActivityPolicy
{

public function view(User $user, Activity $activity)
{
    // administrátor
    if ($user->hasRoleOrHigher(UserRole::ADMIN)) {
        return true;
    }

    // uzivatel ma pravo zobrazit kampan
    if ($activity->step && $activity->step->campaign) {
        if (! $user->can('view', $activity->step->campaign)) {
            return false;
        }
        //  muze zobrazit kampan, neni potreba kontrolovat dal
        if ($user->can('view', $activity->step->campaign)) {
            return true;
        }
    }

    $step = $activity->step;
    $campaign = $step->campaign;

    if (!$campaign) {
        return false;
    }

    // správce kampaně
    if ($campaign->user_id === $user->id) {
        return true;
    }

    return false;
}

    public function assignWorker(User $user, Activity $activity): bool
    {
        $step = $activity->step;
        $campaign = $step?->campaign;

        // ADMIN
        if ($user->hasRoleOrHigher(UserRole::ADMIN)) {
            return true;
        }

        // Spravce kampane (vlastnik kampane)
        if ($campaign && $campaign->user_id === $user->id) {
            return true;
        }

        // Koordinator kroku (vlastnik kroku)
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

        // Spravce kampane
        if ($campaign && $campaign->user_id === $user->id) {
            return true;
        }

        // Koordinator kroku
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

        // Spravce kampane
        if ($campaign && $campaign->user_id === $user->id) {
            return true;
        }

        // Koordinator kroku
        if ($step && $step->user_id === $user->id) {
            return true;
        }

        return false;
    }
    
}
