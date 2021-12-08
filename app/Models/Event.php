<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\EventDays;

class Event extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event_name', 'start_date', 'end_date'
    ];

    protected $dates = ['created_at', 'updated_at', 'start_date', 'end_date'];

    public function days()
    {
        return $this->hasOne(EventDays::class);
    }
}
