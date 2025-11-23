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
        // must be allowed to view the campaign first
        if (! $user->can('view', $campaign)) {
            return false;
        }

        // Admin
        if ($user->hasRoleOrHigher(UserRole::ADMIN)) {
            return true;
        }

        // campaign manager of this campaign (role campaign_manager or higher)
        if ($user->hasRoleOrHigher(UserRole::CAMPAIGN_MANAGER)
            && $campaign->user_id === $user->id) {
            return true;
        }

        // coordinator of any step in this campaign
        if ($campaign->steps()->where('user_id', $user->id)->exists()) {
            return true;
        }

        // worker assigned to this campaign
        if ($campaign->users()->where('campaign_user.user_id', $user->id)->exists()) {
            return true;
        }

        // fallback (no access to manage/list steps)
        return false;
    }

    /**
     * view concrete step, if we want to change the viewing logic
     */
    public function view(User $user, CampaignStep $step): bool
    {
        // must be allowed to view the campaign first
        if (! $step->campaign || ! $user->can('view', $step->campaign)) {
            return false;
        }

        // Admin 
        if ($user->hasRoleOrHigher(UserRole::ADMIN)) {
            return true;
        }

        // campaign_manager
        if ($step->campaign?->user_id === $user->id) {
            return true;
        }

        // coordinator of any step in this campaign (read-only access)
        if ($step->campaign
            && $step->campaign->steps()->where('user_id', $user->id)->exists()) {
            return true;
        }

        // worker assigned to this campaign (read-only access to step detail)
        if ($step->campaign
            && $step->campaign->users()->where('campaign_user.user_id', $user->id)->exists()) {
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
