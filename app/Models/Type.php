<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function activities()
    {
        return $this->hasMany(Activity::class, 'type_id');
    }
}
