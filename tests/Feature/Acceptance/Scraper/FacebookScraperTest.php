<?php

namespace Tests\Feature\Acceptance\Scraper;

use App\Scrapers\FacebookScraper;
use App\Services\Facebook;
use Illuminate\Support\Collection;
use Tests\TestCase;

class FacebookScraperTest extends TestCase
{
    /**
     * @var FacebookScraper
     */
    private $facebookScraper;
    
    public function setUp()
    {
        parent::setUp();
    
        $this->facebookScraper = new FacebookScraper(app()->make(Facebook::class));
    
        \VCR\VCR::configure()
            ->enableRequestMatchers(['method', 'url', 'host', 'query_string']);
    }
    
    /**
     * @test
     * @vcr facebook.places.json
     */
    function it_can_fetch_places()
    {
        $results = $this->facebookScraper->fetchPlaces();
        
        $this->assertInstanceOf(Collection::class, $results);
    }
    
    /**
     * @test
     * @vcr facebook.places.json
     */
    function it_can_fetch_places_with_limit_options()
    {
        $results = $this->facebookScraper->fetchPlaces([
            'limit' => 5
        ]);
        
        $this->assertInstanceOf(Collection::class, $results);
        $this->assertEquals(5, $results->count());
    }
    
    /**
     * @test
     */
    function it_can_save_place_to_database()
    {
        $results = $this->facebookScraper->fetchPlaces();
        
        $this->facebookScraper->savePlaces($results);
        
        $this->assertDatabaseHas('place', [
        
        ]);
    }
}
