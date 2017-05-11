<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use ScoutEngines\Elasticsearch\ElasticSearchable;

class Place extends Model
{
    use ElasticSearchable;
    
    protected $fillable = [
        'facebook_id',
        'short_description',
        'description',
        'location',
        'name',
        'rating',
        'phone',
        'website',
        'extra_info'
    ];
    
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'location' => 'array',
        'extra_info' => 'array',
    ];
    
    /**
     * Get the category of place.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(PlaceCategory::class);
    }
    
    /**
     * Get events of this place.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }
    
    /**
     * Get a string path for the place.
     *
     * @return string
     */
    public function path()
    {
        return "/places/{$this->id}";
    }
    
    /**
     * Array format of the place, that will be indexed by Laravel Scout.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $data = $this->toArray();
        
        if ($data['extra_info'] && array_key_exists('id', $data['extra_info'])) {
            unset($data['extra_info']['id']);
        }
        
        if (array_key_exists('longitude', $this->location)
            && array_key_exists('latitude', $this->location)
        )
        $data['location'] = [
            $this->location['longitude'],
            $this->location['latitude'],
        ];
        
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
            'facebook_id' => ['type' => 'long'],
            'category_id' => ['type' => 'integer'],
            'name' => ['type' => 'text'],
            'description' => ['type' => 'text'],
            'short_description' => ['type' => 'text'],
            'rating' => ['type' => 'float'],
            'phone' => ['type' => 'text'],
            'website' => ['type' => 'text'],
            'location' => ['type' => 'geo_point'],
            'created_at' => [
                'type' => 'date',
                'format' => 'yyyy-MM-dd HH:mm:ss||epoch_millis'
            ],
            'updated_at' => [
                'type' => 'date',
                'format' => 'yyyy-MM-dd HH:mm:ss||epoch_millis'
            ],
        ];
    }
}
