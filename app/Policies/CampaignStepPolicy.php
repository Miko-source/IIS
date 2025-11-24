<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Campaign;
use App\Models\CampaignStep;
use App\Models\User;

class CampaignStepPolicy
{
    /**
     * Zobrazení seznamu kroků
     * (konkrétní kroky se filtrují přes scope)
     */
    public function viewAny(User $user, Campaign $campaign): bool
    {
        // nejdřív musí mít právo zobrazit kampaň
        if (! $user->can('view', $campaign)) {
            return false;
        }

        // Admin
        if ($user->hasRoleOrHigher(UserRole::ADMIN)) {
            return true;
        }

        // správce této kampaně (role campaign_manager nebo vyšší)
        if ($user->hasRoleOrHigher(UserRole::CAMPAIGN_MANAGER)
            && $campaign->user_id === $user->id) {
            return true;
        }

        // koordinátor libovolného kroku v kampani
        if ($campaign->steps()->where('user_id', $user->id)->exists()) {
            return true;
        }

        // pracovník přiřazený ke kampani
        if ($campaign->users()->where('campaign_user.user_id', $user->id)->exists()) {
            return true;
        }

        // fallback (žádný přístup ke správě/výpisu kroků)
        return false;
    }

    /**
     * Zobrazení konkrétního kroku, pokud chceme odlišnou logiku zobrazení
     */
    public function view(User $user, CampaignStep $step): bool
    {
        // nejdřív musí mít právo zobrazit kampaň
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

        // koordinátor libovolného kroku v kampani (pouze čtení)
        if ($step->campaign
            && $step->campaign->steps()->where('user_id', $user->id)->exists()) {
            return true;
        }

        // pracovník přiřazený ke kampani (pouze čtení detailu kroku)
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

        // campaign_manager může vytvářet kroky
        return $campaign->user_id === $user->id;
    }

    public function update(User $user, CampaignStep $step): bool
    {
        if ($user->hasRoleOrHigher(UserRole::ADMIN)) {
            return true;
        }

        // campaign_manager nebo koordinátor může upravovat kroky
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


        // campaign_manager nebo koordinátor
        return $step->campaign?->user_id === $user->id
            || $step->user_id === $user->id;
    }
}
