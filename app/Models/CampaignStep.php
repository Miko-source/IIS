<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function coordinator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class, 'step_id');
    }

    public function isCompletedSuccessfully(): bool
    {
        foreach ($this->activities as $activity) {
            $message = $activity->messages()->latest()->first();

            if (!$message) {
                return false;
            }

            if (!$message->success) {
                return false;
            }
        }

        return true;
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
