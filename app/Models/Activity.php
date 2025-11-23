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
        'completed',
        'cost',
        'description',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'cost' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'completed' => 'boolean',
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
                ->withPivot('is_confirmed', 'is_completed')
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
        return $this->hasConfirmedWorkers();
    }

    public function hasIncompleteConfirmedWorkers(): bool
    {
        return $this->users()
            ->wherePivot('is_confirmed', 1)
            ->where(function($query) {
                $query->where('activity_user.is_completed', false)
                      ->orWhereNull('activity_user.is_completed');
            })
            ->exists();
    }

    public function hasConfirmedWorkers(): bool
    {
        return $this->users()
            ->wherePivot('is_confirmed', 1)
            ->exists();
    }

    public function hasPendingMessages(): bool
    {
        if ($this->relationLoaded('messages')) {
            return $this->messages->contains(fn ($message) => $message->success === null);
        }

        return $this->messages()->whereNull('success')->exists();
    }

    public function hasMessages(): bool
    {
        if ($this->relationLoaded('messages')) {
            return $this->messages->isNotEmpty();
        }

        return $this->messages()->exists();
    }

    public function approvedUsers()
    {
        return $this->belongsToMany(User::class, 'activity_user')
            ->withPivot('is_confirmed', 'is_completed')
            ->wherePivot('is_confirmed', 1)
            ->withTimestamps();
    }


public function isCompleted(): bool
{
    return $this->completed === true;
}

public function allWorkersCompleted(): bool
{
    // // Pokud existuje pending uživatel → aktivita nemůže být dokončena
    // if ($this->users()->wherePivot('is_confirmed', 0)->exists()) {
    //     return false;
    // }

    // Pokud existuje potvrzený uživatel bez dokončení → aktivita nemůže být dokončena
    if ($this->users()
        ->wherePivot('is_confirmed', 1)
        ->wherePivot('is_completed', 0)
        ->exists()) 
    {
        return false;
    }

    // Pokud nemáme žádné potvrzené → aktivita nemůže být dokončena
    if (!$this->users()->wherePivot('is_confirmed', 1)->exists()) {
        return false;
    }

    return true;
}
    public function recalculateCompletion()
{
    if ($this->allWorkersCompleted()) {
        $this->completed = true;
    } else {
        $this->completed = false;
    }

    $this->save();
}



}
