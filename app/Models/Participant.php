<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $fillable = [
        'event_id',
        'user_id',
        'participation_status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
