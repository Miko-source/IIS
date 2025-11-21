<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Campaign;
use App\Models\CampaignStep;
use App\Models\User;

class CampaignStepPolicy
{
    /**
     * view list of steps
     * (concrete steps are filtered by scope)
     */
    public function viewAny(User $user, Campaign $campaign): bool
    {
        // Admin
        if ($user->hasRoleOrHigher(UserRole::ADMIN)) {
            return true;
        }

        // campaign_manager
        if ($campaign->user_id === $user->id) {
            return true;
        }

        // coordinator sees only their steps
        return $campaign->steps()->where('user_id', $user->id)->exists();
    }

    /**
     * view concrete step
     */
    public function view(User $user, CampaignStep $step): bool
    {
        // Admin 
        if ($user->hasRoleOrHigher(UserRole::ADMIN)) {
            return true;
        }

        // campaign_manager
        if ($step->campaign?->user_id === $user->id) {
            return true;
        }

        // coordinator sees only their steps
        return $step->user_id === $user->id;
    }

    public function create(User $user, Campaign $campaign): bool
    {
        if ($user->hasRoleOrHigher(UserRole::ADMIN)) {
            return true;
        }

        // campaign_manager can create steps
        return $campaign->user_id === $user->id;
    }

    public function update(User $user, CampaignStep $step): bool
    {
        if ($user->hasRoleOrHigher(UserRole::ADMIN)) {
            return true;
        }

        // campaign_manager or coordinator can update steps
        return $step->campaign?->user_id === $user->id
            || $step->user_id === $user->id;
    }

    public function delete(User $user, CampaignStep $step): bool
    {
        if ($user->hasRoleOrHigher(UserRole::ADMIN)) {
            return true;
        }

        // campaign_manager
        return $step->campaign?->user_id === $user->id;
    }

    public function markComplete(User $user, CampaignStep $step): bool
    {
        if ($user->hasRoleOrHigher(UserRole::ADMIN)) {
            return true;
        }


        // campaign_manager or coordinator
        return $step->campaign?->user_id === $user->id
            || $step->user_id === $user->id;
    }
}