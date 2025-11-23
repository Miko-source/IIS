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

        // worker assigned to the campaign
        if ($campaign->users()->where('campaign_user.user_id', $user->id)->exists()) {
            return true;
        }

        // coordinator who has at least one step in the campaign
        if ($campaign->steps()->where('user_id', $user->id)->exists()) {
            return true;
        }
        return false;
    }

    /**
     * view concrete step, if we want to change the viewing logic
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

        // worker assigned to the campaign
        if ($step->campaign
            && $step->campaign->users()->where('campaign_user.user_id', $user->id)->exists()
        ) {
            return true;
        }

        // coordinator who has at least one step in the campaign
        if ($step->campaign
            && $step->campaign->steps()->where('user_id', $user->id)->exists()
        ) {
            return true;
        }

        // fallback
        return false;
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
