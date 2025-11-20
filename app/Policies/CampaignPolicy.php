<?php
/**
 * ---------------------------------------------------------
 * Autor:  Martin Bureš
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

        // správce kampaně
        if ($campaign->user_id === $user->id) {
            return true;
        }

        // přidělený uživatel (viditelnost přes pivot tabulku)
        return $campaign->users()
            ->where('campaign_user.user_id', $user->id)
            ->exists();
    }
    
    public function create(User $user): bool
    {
        return $user->hasRoleOrHigher(UserRole::ADMIN);
    }

    // admin nebo přiřazený správce kampaně
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

 // správce kampaně nebo admin může upravovat kampaň

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



}
