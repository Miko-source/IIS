<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Message;

class MessagePolicy
{
    public function view(User $user, Message $message): bool
    {
        // autor zprávy
        if ($message->user_id === $user->id) {
            return true;
        }

        //  správce kampaně
        if (
            $message->activity &&
            $message->activity->step &&
            $message->activity->step->campaign &&
            $message->activity->step->campaign->user_id === $user->id
        ) {
            return true;
        }

        // admin
        if ($user->hasRoleOrHigher(\App\Enums\UserRole::ADMIN)) {
            return true;
        }

        return false;
    }
}
