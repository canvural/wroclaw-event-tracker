<?php

namespace App\Scrapers;

use App\Services\Facebook;
use Illuminate\Support\Collection;

class FacebookScraper implements Scraper
{
    /**
     * @var Facebook
     */
    private $fb;
    
    public function __construct(Facebook $fb)
    {
        $this->fb = $fb;
    }
    
    public function fetch(array $options = []) : Collection
    {
        return collect([1,2,3,4,5]);
    }
}