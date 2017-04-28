<?php

namespace App\Scrapers\Facebook;

use App\Services\Facebook;

abstract class FacebookScraper
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