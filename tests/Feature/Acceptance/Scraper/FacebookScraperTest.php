<?php

namespace Tests\Feature\Acceptance\Scraper;

use App\Scrapers\FacebookScraper;
use App\Services\Facebook;
use Mockery;
use Tests\TestCase;

class FacebookScraperTest extends TestCase
{
    /** @var Facebook|Mockery\MockInterface */
    protected $mockedFacebook;
    
    function setUp()
    {
        parent::setUp();
        
        $this->mockedFacebook = Mockery::mock(Facebook::class);
        
        app()->instance(Facebook::class, $this->mockedFacebook);
    }
    
    
}
