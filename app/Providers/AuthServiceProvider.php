<?php

namespace App\Providers;

use App\Models\Campaign;
use App\Models\CampaignStep;
use App\Models\Topic;
use App\Policies\CampaignPolicy;
use App\Policies\CampaignStepPolicy;
use App\Policies\TopicPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     */
    protected $policies = [
        Campaign::class => CampaignPolicy::class,
        CampaignStep::class => CampaignStepPolicy::class,
        \App\Models\Message::class => \App\Policies\MessagePolicy::class,
        Topic::class => TopicPolicy::class,
    ];



    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
