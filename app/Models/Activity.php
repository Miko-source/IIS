<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Activity extends Model
{
    protected $fillable = [
        'name',
        'step_id',
        'type_id',
        'cost',
        'description',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'cost' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function step(): BelongsTo
    {
        return $this->belongsTo(CampaignStep::class, 'step_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'activity_user')
            ->withPivot('is_confirmed')
            ->withTimestamps();
    }

    public function workers(): BelongsToMany
    {
        return $this->users();
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function latestMessage(): HasOne
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    public function isEvaluated(): bool
    {
        return $this->latestMessage && $this->latestMessage->success !== null;
    }

    public function approvedUsers()
{
    return $this->belongsToMany(User::class, 'activity_user')
        ->withPivot('is_confirmed')
        ->wherePivot('is_confirmed', 1)
        ->withTimestamps();
}

}
