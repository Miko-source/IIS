<?php
/**
 * ---------------------------------------------------------
 * Author:  Martin Bureš
 * Login:  xbures38
 * ---------------------------------------------------------
 */

namespace App\Policies;

use App\Models\User;
use App\Enums\UserRole;
use App\Models\Campaign;

class CampaignPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, Campaign $campaign): bool
    {
        // admin
        if ($user->hasRoleOrHigher(UserRole::ADMIN)) {
            return true;
        }

        // campaign manager with role at least campaign_manager
        if ($user->hasRoleOrHigher(UserRole::CAMPAIGN_MANAGER)
            && $campaign->user_id === $user->id) {
            return true;
        }

        // worker added to this campaign (pivot campaign_user)
        if ($campaign->users()
            ->where('campaign_user.user_id', $user->id)
            ->exists()) {
            return true;
        }

        // coordinator of any step in this campaign
        if ($campaign->steps()
            ->where('user_id', $user->id)
            ->exists()) {
            return true;
        }

        // user assigned to any activity in this campaign
        if ($campaign->steps()
            ->whereHas('activities.users', function ($q) use ($user) {
                $q->where('activity_user.user_id', $user->id);
            })
            ->exists()) {
            return true;
        }

        return false;
    }
    
    public function create(User $user): bool
    {
        return $user->hasRoleOrHigher(UserRole::ADMIN);
    }

    // admin or assigned campaign manager
    public function update(User $user, Campaign $campaign): bool
    {
        return $user->hasRoleOrHigher(UserRole::ADMIN)
            || ($campaign->user_id === $user->id
                && $user->hasRoleOrHigher(UserRole::CAMPAIGN_MANAGER));
    }

    public function delete(User $user, Campaign $campaign): bool
    {
        return $user->hasRoleOrHigher(UserRole::ADMIN);
    }

 // campaign manager or admin can edit campaign

    public function manageManager(User $user): bool
    {
        return $user->hasRoleOrHigher(UserRole::ADMIN);
    }
    
    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user): bool
    {
        return false;
    } 

    /**
     * Determine whether the user can assign a manager to the campaign.
     */
    public function assignManager(User $user, Campaign $campaign): bool
    {
        return $user->hasRoleOrHigher(UserRole::ADMIN);
    }
    public function editManager(User $user, Campaign $campaign): bool
    {
        return $user->hasRoleOrHigher(UserRole::ADMIN);
    }
    public function updateManager(User $user, Campaign $campaign): bool
    {
        return $user->hasRoleOrHigher(UserRole::ADMIN);
    }
//////////////////////////////// added ///////////////////////////////////////


    public function manageWorkers(User $user, Campaign $campaign)
    {
        // ADMIN role
        if ($user->role instanceof UserRole && $user->role === UserRole::ADMIN) {
            return true;
        }

        // Campaign manager (campaign owner)
        if ($user->id === $campaign->user_id) {
            return true;
        }

        return false;
    }
    





}
