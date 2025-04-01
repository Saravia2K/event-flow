<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'generated_by',
        'file_path',
        'event_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "generated_by");
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
