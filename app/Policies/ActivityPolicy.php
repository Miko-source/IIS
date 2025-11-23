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

    // musí mít možnost vidět kampaň
    if ($activity->step && $activity->step->campaign) {
        if (! $user->can('view', $activity->step->campaign)) {
            return false;
        }
        // pokud uživatel může vidět kampaň, další kontroly nejsou potřeba
        if ($user->can('view', $activity->step->campaign)) {
            return true;
        }
    }

    // pokud krok chybí, povolit zobrazení
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

    public function assignWorker(User $user, Activity $activity): bool
    {
        $step = $activity->step;
        $campaign = $step?->campaign;

        // ADMIN → vždy může
        if ($user->hasRoleOrHigher(UserRole::ADMIN)) {
            return true;
        }

        // Správce kampaně (owner kampaně)
        if ($campaign && $campaign->user_id === $user->id) {
            return true;
        }

        // Koordinátor kroku (owner kroku)
        if ($step && $step->user_id === $user->id) {
            return true;
        }

        return false;
    }

        public function manageWorkers(User $user, Activity $activity): bool
    {
        $step     = $activity->step;
        $campaign = $step?->campaign;

        // ADMIN
        if ($user->hasRoleOrHigher(UserRole::ADMIN)) {
            return true;
        }

        // SPRÁVCE KAMPANĚ
        if ($campaign && $campaign->user_id === $user->id) {
            return true;
        }

        // KOORDINÁTOR KROKU
        if ($user->hasRole(UserRole::COORDINATOR) && $step && $step->user_id === $user->id) {
            return true;
        }

        return false;
    }
    
}
