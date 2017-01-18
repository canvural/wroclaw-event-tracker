<?php

namespace App\Console\Commands;

use App\Models\Place;
use App\Models\PlaceCategory;
use App\Scrapers\FacebookScraper;
use Illuminate\Console\Command;

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
        $pb = $this->output->createProgressBar();
        $pb->start();
        
        $results = collect($this->facebookScraper->fetchPlaces([
            'limit' => 2000
        ])['data']);
        
        $results->each(function ($test) use($pb){
            $test['short_description'] = $test['about'] ?? '';
            unset($test['about']);
    
            $test['facebook_id'] = $test['id'] ?? null;
            unset($test['id']);
    
            $test['rating'] = $test['overall_star_rating'] ?? 0;
            unset($test['overall_star_rating']);
    
            $c = PlaceCategory::firstOrCreate(['name' => $test['category']]);
    
            unset($test['category']);
    
            $p = new Place($test);
            $p->category_id = $c->id;
            $p->extra_info = (array_diff_key($test, $p->getAttributes()));
            $p->save();
    
            $p = null;
            $c = null;
            $pb->advance();
        });
        
        $pb->finish();
        
        return true;
    }
}
