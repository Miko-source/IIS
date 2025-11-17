<?php

namespace App\Enums;
//   Represents all user roles available in the application.
//   Used for type-safe handling of roles in models, seeders,

enum UserRole: string
{
    case ADMIN = 'admin';
    case CAMPAIGN_MANAGER = 'campaign_manager';
    case COORDINATOR = 'coordinator';
    case WORKER = 'worker';
    case GUEST = 'guest';
}
