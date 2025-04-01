<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventPdf extends Model
{
    protected $fillable = [
        'event_id',
        'file_path',
        'generated_by',
        'generated_at'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function generator()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}