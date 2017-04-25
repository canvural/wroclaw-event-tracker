<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
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
}
