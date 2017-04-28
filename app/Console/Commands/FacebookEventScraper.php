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
    protected $signature = 'scrape:facebook:events';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches events of all of the places.';
    
    /**
     * @var EventScraper
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
        $progressBar = $this->startProgressBar(Place::count());
        
        foreach (Place::select(['id', 'name','facebook_id'])->cursor() as $place) {
            $progressBar->setMessage("Fetching events for " . $place['name']);
            $events = $this->facebookEventScraper->fetch($place['facebook_id']);
    
            $progressBar->setMessage("Transforming events for " . $place['name']);
            $events = $this->facebookEventScraper->transformToModel($place['id'], $events);
    
            $progressBar->setMessage("Saving events for " . $place['name']);
            $this->facebookEventScraper->save($events);
            
            $progressBar->advance();
        }
    
        $progressBar->finish();
        
        return;
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
    
        $pb->setFormat("\n%message%\n %current%/%max% [%bar%] %percent:3s%%");
        
        return $pb;
    }
}
