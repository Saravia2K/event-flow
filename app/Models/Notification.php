<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'event_id',
        'title',
        'message',
        'is_read'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }

    public function event()
    {
        return $this->belongsTo(Event::class, "event_id", "id");
    }
}
