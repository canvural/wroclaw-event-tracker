<?php

namespace App\Console\Commands;

use App\Models\Place;
use App\Models\PlaceCategory;
use App\Scrapers\FacebookScraper;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;

class FacebookPlaceScraper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:places';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch all of the available places from the Facebook API';
    
    /**
     * @var FacebookScraper
     */
    private $facebookScraper;
    
    /**
     * Create a new command instance.
     *
     * @param FacebookScraper $facebookScraper
     */
    public function __construct(FacebookScraper $facebookScraper)
    {
        parent::__construct();
        $this->facebookScraper = $facebookScraper;
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
        $results = collect($this->facebookScraper->fetchPlaces([
            'limit' => 2000
        ])['data']);
    
        $this->facebookScraper->savePlaces($results, function ($place) use($progressBar) {
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
        $pb = $this->output->createProgressBar($max);
        $pb->start();
        
        return $pb;
    }
}
