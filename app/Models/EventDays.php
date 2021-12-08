<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventDays extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'event_id'
    ];
}
