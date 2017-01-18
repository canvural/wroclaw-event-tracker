<?php

namespace App\Scrapers;

use App\Models\Place;
use App\Services\Facebook;
use Illuminate\Support\Collection;

class FacebookScraper implements Scraper
{
    /**
     * @var Facebook
     */
    private $fb;
    
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
    
    public function __construct(Facebook $fb)
    {
        $this->fb = $fb;
    }
    
    public function fetch(array $options): Collection
    {
        return collect([1, 2, 3, 4, 5,]);
    }
    
    /**
     * Fetches all Facebook Places in Wroclaw from the API.
     *
     * It can take some options like limit or distance.
     * See @getDefaultOptions method.
     *
     * @param array $options
     *
     * @return Collection
     */
    public function fetchPlaces(array $options = []): Collection
    {
        $req = $this->fb->sendRequest('GET', 'search', array_merge(
            $this->getDefaultOptions(), $options
        ));
        
        return collect($req->getDecodedBody());
    }
    
    public function savePlaces(Collection $places)
    {
        $places->each(function ($place) {
            $this->savePlace($place);
        });
    }
    
    private function savePlace(array $place)
    {
        $place = new Place($place);
        
        $place->getAttributes();
    }
    
    /**
     * Default options that will be merged with user provided
     * options.
     *
     * @return array
     */
    private function getDefaultOptions(): array
    {
        return [
            'limit' => 2,
            'type' => 'place',
            'center' => '51.107885,17.038538', // Wroclaw
            'distance' => 30000,
            'fields' => join(',', $this->placeFields),
        ];
    }
}