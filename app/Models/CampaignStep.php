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
        'user_id'
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
    

}
