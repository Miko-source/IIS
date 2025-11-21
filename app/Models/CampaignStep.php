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

    /**
     * Zkontroluje, zda jsou všechny aktivity kroku úspěšně dokončeny
     * 
     * @return bool true pokud VŠECHNY aktivity mají poslední zprávu s success = 1
     */
    public function isCompletedSuccessfully(): bool
    {
        // Pokud krok nemá žádné aktivity, nemůže být dokončen
        if ($this->activities->count() === 0) {
            return false;
        }

        foreach ($this->activities as $activity) {
            $lastMessage = $activity->messages()->latest()->first();

            // Pokud aktivita nemá žádnou zprávu, není dokončena
            if (!$lastMessage) {
                return false;
            }

            // Pokud poslední zpráva není explicitně úspěšná (success !== 1), není dokončena
            // To znamená, že success = 0 (neúspěch) nebo success = null (nedokončeno) = NESPLNĚNO
            if ($lastMessage->success !== 1) {
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