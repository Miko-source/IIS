<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case CAMPAIGN_MANAGER = 'campaign_manager';
    case COORDINATOR = 'coordinator';
    case WORKER = 'worker';
    case DEACTIVATED = 'deactivated';

}
