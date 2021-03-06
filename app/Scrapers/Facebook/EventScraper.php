<?php

namespace App\Scrapers\Facebook;

use App\Models\Event;
use App\Models\Place;
use Carbon\Carbon;
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
        'end_time',
        'start_time',
        'name',
    ];
    
    protected $extraFields = [
        'cover',
        'category',
        'attending_count',
        'can_guests_invite',
        'declined_count',
        'guest_list_enabled',
        'is_canceled',
        'interested_count',
        'is_page_owned',
        'is_viewer_admin',
        'maybe_count',
        'noreply_count',
        'parent_group',
        'owner',
        'ticket_uri',
        'timezone',
        'type',
        'updated_time',
        'attending',
        'comments',
        'photos',
        'posts',
        'picture'
    ];
    
    /**
     * @param $placeId
     * @param array $options
     * @return Collection
     */
    public function fetch($placeId, array $options = []): Collection
    {
        $events = $this->fb->fetchAllEventsForPlace($placeId, $this->getOptions($options));

        usleep(700);
        
        return $events;
    }
    
    /**
     * @param $placeId
     * @param Collection $rawEvents
     * @return Collection
     */
    public function transformToModel($placeId, Collection $rawEvents): Collection
    {
        return $rawEvents->map(function ($eventData) use($placeId) {
            $eventData['place_id'] = $placeId;
            
            return Event::firstOrNew([
                'facebook_id' => $eventData['id']
            ], $this->transform($eventData));
        });
    }
    
    /**
     * @inheritdoc
     */
    public function save(Collection $models, $options = []): bool
    {
        $models->reject(function ($event) {
            return $event->exists;
        })->each(function ($model) use ($options) {
            try {
                $model->saveOrFail();
            } catch (\Illuminate\Database\QueryException $e) {
                // do something maybe..
            }
        });
        
        return true;
    }
    
    /**
     * Transform the raw event data into sutiable format for the Event model.
     *
     * @param array $eventData
     * @return array
     */
    private function transform(array $eventData) : array
    {
        $eventData['extra_info'] = array_diff_key($eventData, array_flip($this->eventFields));
        
        $eventData['facebook_id'] = $eventData['id'] ?? null;
        unset($eventData['id']);
        
        //$eventData['category_id'] = $eventData['extra_info']['category'] ?? null;
    
        if (array_key_exists('start_time', $eventData)) {
            $eventData['start_time'] = Carbon::parse($eventData['start_time'])->toDateTimeString();
        }
    
        if (array_key_exists('end_time', $eventData)) {
            $eventData['end_time'] = Carbon::parse($eventData['end_time'])->toDateTimeString();
        }
    
        return $eventData;
    }
    
    private function getOptions($options)
    {
        return array_merge(
            $this->getDefaultOptions(), $options
        );
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
            'fields' => $this->getEventFields(),
        ];
    }
    
    /**
     * Get the string representaiton of the fields
     * we will fetch from API.
     *
     * @return string
     */
    private function getEventFields()
    {
        return join(',', $this->eventFields) . ',' . join(',', $this->extraFields);
    }
}