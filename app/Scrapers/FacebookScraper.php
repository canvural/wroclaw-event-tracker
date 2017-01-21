<?php

namespace App\Scrapers;

use App\Models\Place;
use App\Models\PlaceCategory;
use App\Services\Facebook;
use Illuminate\Database\Eloquent\Collection as ModelCollection;
use Illuminate\Support\Collection;

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