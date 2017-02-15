<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Place;
use App\Scrapers\Facebook\EventScraper;
use Carbon\Carbon;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookServerException;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Helper\ProgressBar;

class FacebookEventScraper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:events';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrapes events from the all available websites and APIs';
    
    /**
     * @var FacebookEventScraper
     */
    private $facebookEventScraper;
    
    /**
     * Create a new command instance.
     *
     * @param EventScraper $facebookEventScraper
     */
    public function __construct(EventScraper $facebookEventScraper)
    {
        parent::__construct();
        
        $this->facebookEventScraper = $facebookEventScraper;
        
        \Log::useFiles(storage_path().'/logs/event-scraper.log');
    }
    
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // Get all places
        $places = Place::all();
    
        // Create progress with maximum of count of places.
        $progressBar = $this->startProgressBar($places->count());
        
        /** @var Place $place */
        $places->each(function ($place) use($progressBar) {
            $progressBar->setMessage("Fetching events for " . $place->name);
    
            try {
                $events = $this->fetchAllPlaceEvents($place)->filter(function ($value, $key) {
                    return !empty($value);
                });
            } catch (FacebookServerException|FacebookResponseException $e) {
                \Log::error($e);
                sleep(2);
                return;
            }
            
            if ($events->isNotEmpty()) {
                $progressBar->setMessage("\tSaving events for " . $place->name);
    
                try {
                    $this->saveEvents($place, $events);
                } catch (QueryException $e){
                    $errorCode = $e->errorInfo[1];
                    // Duplicate entry
                    if($errorCode == 1062){
                        return;
                    }
    
                    \Log::error($e);
                }
            } else {
                $progressBar->setMessage("\tNo event found!");
            }
            
            $progressBar->advance();
        });
    }
    
    private function saveEvents(Place $place, Collection $events)
    {
        $progressBar = $this->startProgressBar($events->count());
        
        $events->each(function ($event) use($progressBar, $place) {
            $eventModel = new Event($this->transfromToModelArray($event));
            $eventModel->place_id = $place->id;
            $eventModel->extra_info = (array_diff_key($event, $eventModel->getAttributes()));
    
            if (array_key_exists('category', $event)) {
                /** @var EventCategory $category */
                $category = EventCategory::firstOrCreate(['name' => $event['category']]);
    
                $category->events()->save($eventModel);
                
                $category = null;
            } else {
                $eventModel->save();
            }
    
            $eventModel = null;
            
            $progressBar->advance();
        });
    
        $progressBar->finish();
    }
    
    protected function fetchAllPlaceEvents(Place $place) : Collection
    {
        $events = $this->facebookEventScraper->fetchEvents($place, ['limit' => 2000]);
        $paging = $events['paging'] ?? [];
        
        while ($this->hasNext($paging)) {
            $events->merge($this->facebookEventScraper->fetchEvents($place, [
                'limit' => 2000,
                'after' => $this->getAfter($paging)
            ]));
        }
        
        return collect($events['data']);
    }
    
    /**
     * Transform Facebook Event array to array that our Event model expects.
     *
     * @param array $event Array containing the event information fetched from Facebook API.
     * @return array
     */
    private function transfromToModelArray(array $event): array
    {
        $event['facebook_id'] = $event['id'] ?? null;
        unset($event['id']);
    
        if (array_key_exists('start_time', $event)) {
            $event['start_time'] = Carbon::parse($event['start_time'])->toDateTimeString();
        }
    
        if (array_key_exists('end_time', $event)) {
            $event['end_time'] = Carbon::parse($event['end_time'])->toDateTimeString();
        }
        
        return $event;
    }
    
    /**
     * Create and start a progress bar.
     *
     * @param int $max
     * @return ProgressBar
     */
    private function startProgressBar($max = 0): ProgressBar
    {
        $pb = $this->output->createProgressBar($max);
        $pb->start();
    
        $pb->setFormat("%message%\n %current%/%max% [%bar%] %percent:3s%%");
        
        return $pb;
    }
    
    /**
     * Check if next set of data exists in paginated results.
     *
     * @param array $paging
     * @return bool
     */
    private function hasNext(array $paging)
    {
        return array_key_exists('next', $paging);
    }
    
    /**
     *
     *
     * @param array $paging
     * @return string
     */
    private function getAfter(array $paging)
    {
        return $paging['after'];
    }
}
