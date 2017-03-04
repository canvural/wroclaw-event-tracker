<?php

namespace App\Scrapers\Facebook;

use App\Scrapers\Scraper;
use App\Services\Facebook;

abstract class FacebookScraper implements Scraper
{
    /**
     * @var Facebook
     */
    protected $fb;
    
    public function __construct(Facebook $fb)
    {
        $this->fb = $fb;
    }
}