<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function step()
    {
        return $this->belongsTo(CampaignStep::class, 'step_id');
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    public function workers()
    {
        return $this->belongsToMany(User::class, 'activity_user')
                    ->withPivot('is_confirmed')
                    ->withTimestamps();
    }

}
