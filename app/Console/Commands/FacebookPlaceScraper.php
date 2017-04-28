<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Scrapers\Facebook\PlaceScraper;
use Symfony\Component\Console\Helper\ProgressBar;

class FacebookPlaceScraper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:facebook:places';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch all of the available places from the Facebook API';
    
    /**
     * @var PlaceScraper
     */
    private $facebookPlaceScraper;
    
    /**
     * Create a new command instance.
     *
     * @param PlaceScraper $facebookEventScraper
     */
    public function __construct(PlaceScraper $facebookEventScraper)
    {
        parent::__construct();
        
        $this->facebookPlaceScraper = $facebookEventScraper;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $progressBar = $this->startProgressBar();
        
        // Fetch places from Facebook API
        $results = $this->facebookPlaceScraper->fetchPlaces([
            'limit' => 2000
        ]);
    
        $this->facebookPlaceScraper->savePlaces($results, function ($place) use($progressBar) {
            $progressBar->advance();
        });
        
        $progressBar->finish();
        
        return true;
    }
    
    /**
     * Create and start a progress bar.
     *
     * @param int $max
     * @return ProgressBar
     */
    private function startProgressBar($max = 0): ProgressBar
    {
        return tap($this->output->createProgressBar($max), function ($pb) {
            $pb->start();
        });
    }
}
