<?php

// App\Models\Campaign.php
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
}

