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

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'topic_id',
        'user_id',
        'start_date',
        'end_date',
    ];


    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
    public function steps()
    {
        return $this->hasMany(\App\Models\CampaignStep::class, 'campaign_id');
    }
    // Správce kampaně
    public function manager()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Uživatelé s viditelností kampaně (pivot campaign_user)
    public function users()
    {
        return $this->belongsToMany(User::class, 'campaign_user')->withTimestamps();
    }

}
