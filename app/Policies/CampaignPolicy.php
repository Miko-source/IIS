<?php
namespace App\Policies;

use App\Models\User;
use App\Enums\UserRole;
use App\Models\Campaign;

class CampaignPolicy
{
    /**
     * Určuje, zda může uživatel zobrazit libovolné záznamy.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, Campaign $campaign): bool
    {
        // ADMIN
        if ($user->hasRoleOrHigher(UserRole::ADMIN)) {
            return true;
        }

        // správce kampaně
        if ($user->hasRoleOrHigher(UserRole::CAMPAIGN_MANAGER)
            && $campaign->user_id === $user->id) {
            return true;
        }

        // pracovník přidaný do kampaně (pivot campaign_user)
        if ($campaign->users()
            ->where('campaign_user.user_id', $user->id)
            ->exists()) {
            return true;
        }

        // koordinátor libovolného kroku v kampani
        if ($campaign->steps()
            ->where('user_id', $user->id)
            ->exists()) {
            return true;
        }

        // uživatel přiřazený k libovolné aktivitě v kampani
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

 // správce kampaně nebo admin mohou upravovat kampaň

    public function manageManager(User $user): bool
    {
        return $user->hasRoleOrHigher(UserRole::ADMIN);
    }
    
    public function restore(User $user): bool
    {
        return false;
    }

    public function forceDelete(User $user): bool
    {
        return false;
    } 

    /**
     * Určuje, zda může uživatel přiřadit správce kampani.
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

    public function manageWorkers(User $user, Campaign $campaign)
    {
        // role ADMIN
        if ($user->role instanceof UserRole && $user->role === UserRole::ADMIN) {
            return true;
        }

        // Správce kampaně (vlastník kampaně)
        if ($user->id === $campaign->user_id) {
            return true;
        }

        return false;
    }
    





}
