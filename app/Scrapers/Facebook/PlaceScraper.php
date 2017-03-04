<?php

namespace App\Scrapers\Facebook;

use App\Models\Place;
use App\Models\PlaceCategory;
use Illuminate\Support\Collection;

class PlaceScraper extends FacebookScraper
{
    /**
     * Fields to fetch about a place.
     *
     * @var array
     */
    protected $placeFields = [
        'id',
        'about',
        'attire',
        'can_checkin',
        'category',
        'category_list',
        'checkins',
        'culinary_team',
        'description',
        'food_styles',
        'general_info',
        'hours',
        'is_always_open',
        'is_permanently_closed',
        'link',
        'location',
        'name',
        'overall_star_rating',
        'parking',
        'payment_options',
        'phone',
        'place_type',
        'website'
    ];
    
    /**
     * Fetches all Facebook Places in Wroclaw from the API.
     *
     * It can take some options like limit or distance.
     * See @getDefaultOptions method for options.
     *
     * @param array $options
     *
     * @return Collection
     */
    public function fetchPlaces(array $options = []): Collection
    {
        $req = $this->fb->sendRequest('GET', 'search', array_merge(
            $this->getDefaultOptionsForFetchingPlaces(), $options
        ));
        
        return collect($req->getDecodedBody());
    }
    
    /**
     * Iterates through a collection and saves them to database.
     *
     * @param Collection $places
     * @param callable $callback Callback to run after a place is saved.
     */
    public function savePlaces(Collection $places, callable $callback = null)
    {
        $places->each(function ($place) use($callback) {
            $place = $this->savePlace($place);
            
            if (! is_null($callback)) {
                $callback($place);
            }
        });
    }
    
    /**
     * Save a place to database.
     * Creates the category if not exists.
     *
     * @param array $place
     * @return Place|false
     */
    private function savePlace(array $place)
    {
        $placeModel = new Place($this->transfromToModelArray($place));
        $placeModel->extra_info = (array_diff_key($place, $placeModel->getAttributes()));
        
        /** @var PlaceCategory $category */
        $category = PlaceCategory::firstOrCreate(['name' => $place['category']]);
        
        return $category->places()->save($placeModel);
    }
    
    /**
     * Default options that will be merged with user provided
     * options.
     *
     * @return array
     */
    private function getDefaultOptionsForFetchingPlaces(): array
    {
        return [
            'limit' => 25,
            'type' => 'place',
            'center' => '51.107885,17.038538', // Wroclaw
            'distance' => 30000,
            'fields' => join(',', $this->placeFields),
        ];
    }
    
    /**
     * Transform Facebook Place array to array that our Place model expects.
     *
     * @param array $place Array containing the place information fetched from Facebook API.
     * @return array
     */
    private function transfromToModelArray(array $place): array
    {
        $place['short_description'] = $place['about'] ?? '';
        unset($place['about']);
        
        $place['facebook_id'] = $place['id'] ?? null;
        unset($place['id']);
        
        $place['rating'] = $place['overall_star_rating'] ?? 0;
        unset($place['overall_star_rating']);
        
        return $place;
    }
}