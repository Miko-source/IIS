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
    if ($user->role === 'admin') {
        return true;
    }

    // pokud aktivity nemají step, povolit aby to nespadlo
    if (!$activity->step) {
        return true;
    }

    $step = $activity->step;
    $campaign = $step->campaign;

    //pokud step nemá kampaň
    if (!$campaign) {
        return true;
    }

    // správce kampaně
    if ($campaign->user_id === $user->id) {
        return true;
    }

    // koordinátor kroku
    if ($step->user_id === $user->id) {
        return true;
    }

    // realizátor
    if ($activity->users->contains($user->id)) {
        return true;
    }

    // pracovník
    if ($campaign->workers->contains($user->id)) {
        return true;
    }

    return false;
}


    
}

