<?php


namespace App\Models;
// App\Models\Campaign.php

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

}
