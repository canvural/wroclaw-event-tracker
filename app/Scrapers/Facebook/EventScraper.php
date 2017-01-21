<?php

namespace App\Scrapers\Facebook;

use App\Models\Place;
use App\Scrapers\FacebookScraper;
use Illuminate\Support\Collection;

class EventScraper extends FacebookScraper
{
    /**
     * Fields to fetch about an event.
     *
     * @var array
     */
    protected $eventFields = [
        'id',
        'description',
        'cover',
        'end_time',
        'start_time',
        'category',
        'name'
    ];
    
    /**
     * Fetches events of the given place.
     *
     * @param Place $place
     * @param array $options
     * @return Collection
     */
    public function fetchEvents(Place $place, array $options = []): Collection
    {
        $req = $this->fb->sendRequest('GET', $place->facebook_id . '/events', array_merge(
            $this->getDefaultOptionsForFetchingEvents(), $options
        ));
        
        return collect($req->getDecodedBody());
    }
    
    /**
     * Default options that will be merged with user provided
     * options.
     *
     * @return array
     */
    private function getDefaultOptionsForFetchingEvents(): array
    {
        return [
            'limit' => 25,
            'fields' => join(',', $this->eventFields),
        ];
    }
}