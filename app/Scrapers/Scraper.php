<?php

namespace App\Scrapers;

use Illuminate\Support\Collection;

interface Scraper
{
    public function fetch(array $options) : Collection;
}