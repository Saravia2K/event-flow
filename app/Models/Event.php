<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'status',
        "created_by"
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
        ];
    }

    public function comments()
    {
        return $this->hasMany(EventComment::class)->oldest();
    }

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
