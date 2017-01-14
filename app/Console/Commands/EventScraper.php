<?php

namespace App\Console\Commands;

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
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
    }
}
