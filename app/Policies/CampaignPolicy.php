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
use Illuminate\Auth\Access\Response;

class CampaignPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }
    /**
     * Determine whether the user can view the model.
     */
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


    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRoleOrHigher(UserRole::ADMIN);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Campaign $campaign): bool
    {
        return $campaign->user_id === $user->id || $user->hasRoleOrHigher(UserRole::ADMIN);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return false;
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
}
