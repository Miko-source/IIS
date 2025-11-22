<?php
/**
 * ---------------------------------------------------------
 * Autor:  Martin Bureš
 * Login:  xbures38
 * ---------------------------------------------------------
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\UserRole;
use App\Models\User;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'topic_id',
        'user_id',
        'start_date',
        'end_date',
        'done',
    ];

    protected $casts = [
        'done' => 'boolean',
    ];


    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
    public function steps()
    {
        return $this->hasMany(\App\Models\CampaignStep::class, 'campaign_id');
    }
    public function manager()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function workers()
    {
        return $this->belongsToMany(User::class, 'campaign_user')
            ->withTimestamps();
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'campaign_user')
                    ->withTimestamps();
    }
    public function scopeVisibleFor(Builder $query, User $user): Builder
    {
        if ($user->hasRoleOrHigher(UserRole::ADMIN)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($user) {
            $q->where('campaigns.user_id', $user->id)
                ->orWhereHas('users', function (Builder $userQuery) use ($user) {
                    $userQuery->where('campaign_user.user_id', $user->id);
                })
                ->orWhereHas('steps', function (Builder $stepQuery) use ($user) {
                    $stepQuery->where('user_id', $user->id);
                });
        });
    }
}
