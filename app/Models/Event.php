<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class Event extends Model implements HasMediaConversions
{
    use HasMediaTrait;
    
    protected $fillable = [
        'facebook_id',
        'description',
        'name',
        'end_time',
        'start_time',
        'extra_info',
        'place_id',
        'category_id',
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
        return $this->belongsTo(Place::class)->select('id', 'name', 'location');
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
    
    /**
     * Get human readable event start time.
     *
     * @return mixed
     */
    public function getFormattedStartTimeAttribute()
    {
        if ($this->start_time->isToday()) {
            return 'Today at ' . $this->start_time->format('GA');
        }
    
        if ($this->start_time->isTomorrow()) {
            return 'Tomorrow at ' . $this->start_time->format('GA');
        }
        
        return $this->start_time->format('j F Y G:iA');
    }
    
    /**
     * Get human readable event end time.
     *
     * @return mixed
     */
    public function getFormattedEndTimeAttribute()
    {
        if (is_null($this->end_time)) {
            return '';
        }
        
        if ($this->end_time->isToday() || $this->end_time->isTomorrow()) {
            return $this->end_time->format('GA');
        }
        
        return $this->end_time->format('j F Y G:iA');
    }
    
    /**
     * Query scope to search event name and description
     * for the given search query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string                                $searchQuery
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $searchQuery)
    {
        return $query->when($searchQuery, function($query) use ($searchQuery){
            return $query->where(function ($q) use ($searchQuery) {
                $q->where('description', 'like', '%' . $searchQuery . '%')
                    ->orWhere('name', 'like', '%' . $searchQuery . '%');
            });
        });
    }
    
    /**
     * Register the conversions that should be performed.
     *
     */
    public function registerMediaConversions()
    {
        $this->addMediaConversion('banner')
            ->width(320)
            ->height(160);
    
        $this->addMediaConversion('thumbnail')
            ->width(96)
            ->height(96);
    }
}
