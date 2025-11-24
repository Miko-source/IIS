<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\UserRole;
use App\Models\User;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'target_group',
        'description',
        'sources',
    ];

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    /**
     * Temata viditelna pro daneho uzivatele
     */
    public function scopeVisibleFor(Builder $query, User $user): Builder
    {
        if ($user->hasRoleOrHigher(UserRole::ADMIN)) {
            return $query;
        }

        return $query->whereHas('campaigns', function (Builder $campaignQuery) use ($user) {
            // campaign manager
            $campaignQuery->where('campaigns.user_id', $user->id)
                // prirazeny pracovnik kampane
                ->orWhereHas('users', function (Builder $userQuery) use ($user) {
                    $userQuery->where('campaign_user.user_id', $user->id);
                })
                // koordinator kroku
                ->orWhereHas('steps', function (Builder $stepQuery) use ($user) {
                    $stepQuery->where('user_id', $user->id);
                });
        });
    }
}