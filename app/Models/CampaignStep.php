<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\UserRole;

class CampaignStep extends Model
{
    protected $table = 'steps';

    protected $fillable = [
        'campaign_id',
        'order',
        'name',
        'description',
        'user_id',
        'is_completed',   
    ];

    protected $casts = [
        'is_completed' => 'boolean',  
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class, 'step_id');
    }

    /**
     * this steps activities were all finished successfully
     */
    public function isCompletedSuccessfully(): bool
    {
        if ($this->activities->isEmpty()) {
            return false;
        }

        foreach ($this->activities as $activity) {
            $lastMessage = $activity->messages()->latest()->first();

            if (!$lastMessage) {
                return false;
            }

            if ($lastMessage->success !== 1) {
                return false;
            }
        }

        return true;
    }

    public function scopeVisibleFor($query, User $user, Campaign $campaign)
    {
        if ($user->hasRoleOrHigher(UserRole::ADMIN)) {
            return $query;
        }

        if ($campaign->user_id === $user->id) {
            return $query;
        }

        return $query->where('user_id', $user->id);
    }
}