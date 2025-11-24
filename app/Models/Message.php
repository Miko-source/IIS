<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\AutoPaginate;

class Message extends Model
{
    use HasFactory, AutoPaginate;

    protected $fillable = [
        'activity_id',
        'user_id',
        'content',
        'success',
    ];

    protected $casts = [
        'success' => 'integer',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}