<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'facebook_id',
        'description',
        'name',
        'end_time',
        'start_time',
        'extra_info'
    ];
    
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'extra_info' => 'array'
    ];
    
    protected $dates = [
        'start_time',
        'end_time'
    ];
    
    /**
     * Get the place of this event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function place()
    {
        return $this->belongsTo(Place::class);
    }
    
    /**
     * Get this events category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(EventCategory::class);
    }
}
