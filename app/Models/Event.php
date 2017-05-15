<?php

namespace App\Models;

use App\Filters\EventsFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use ScoutEngines\Elasticsearch\ElasticSearchable;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

class Event extends Model implements HasMediaConversions
{
    use HasMediaTrait, ElasticSearchable;
    
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
    
    public function attendees()
    {
        return $this->belongsToMany(User::class);
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
     * Get the number of attending users for the event.
     *
     * @return integer
     */
    public function getAttendeeCountAttribute()
    {
        return $this->attendees->count();
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
        $this->addMediaConversion('thumbnail')
            ->width(96)
            ->height(96)
            ->performOnCollections('cover');
    
        
            
    }
    
    /**
     * Determines if event is happened.
     *
     * @return boolean
     */
    public function isInFuture()
    {
        return $this->end_time->isFuture();
    }
    
    /**
     * Get a string path for the event.
     *
     * @return string
     */
    public function path()
    {
        return "/events/{$this->id}";
    }
    
    /**
     * @param Builder $query
     * @param EventsFilters $filters
     * @return Builder
     */
    public function scopeFilter($query, EventsFilters $filters)
    {
        return $filters->apply($query);
    }
    
    /**
     * @return string|null
     */
    public function getCoverPictureUrl()
    {
        return $this->extra_info['cover']['source'] ?? null;
    }
    
    /**
     * Array format of the event, that will be indexed by Laravel Scout.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $data = $this->toArray();
        
        if ($data['extra_info'] && array_key_exists('id', $data['extra_info'])) {
            unset($data['extra_info']['id']);
        }
    
        if (array_key_exists('longitude', $this->place->location)
            && array_key_exists('latitude', $this->place->location)
        ) {
            $data['location'] = [
                $this->place->location['longitude'],
                $this->place->location['latitude'],
            ];
        }
        
        return $data;
    }
    
    /**
     * Elasticsearch specific mappings for the event.
     *
     * @return array
     */
    public static function mapping()
    {
        return [
            'id' => ['type' => 'integer'],
            'name' => ['type' => 'text'],
            'end_time' => [
                'type' => 'date',
                'format' => 'yyyy-MM-dd HH:mm:ss||epoch_millis'
            ],
            'start_time' => [
                'type' => 'date',
                'format' => 'yyyy-MM-dd HH:mm:ss||epoch_millis'
            ],
            'created_at' => [
                'type' => 'date',
                'format' => 'yyyy-MM-dd HH:mm:ss||epoch_millis'
            ],
            'updated_at' => [
                'type' => 'date',
                'format' => 'yyyy-MM-dd HH:mm:ss||epoch_millis'
            ],
            'description' => ['type' => 'text'],
            'facebook_id' => ['type' => 'long'],
            'place_id' => ['type' => 'integer'],
            'location' => ['type' => 'geo_point'],
            'category_id' => ['type' => 'integer'],
        ];
    }
}
