<?php

namespace App\Console\Commands;

use App\Scrapers\FacebookScraper;
use Illuminate\Console\Command;

class EventScraper extends Command
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
        $this->facebookScraper->fetch([
            'limit' => 2000
        ]);
    }
}
