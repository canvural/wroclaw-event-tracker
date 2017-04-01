<?php

namespace App\Scrapers;

use Illuminate\Support\Collection;

interface Scraper
{
    /**
     * Fetches data from the data source.
     * And returns the raw data as an array.
     *
     * @param array $options
     * @return Collection
     */
    public function fetch(array $options) : Collection;
    
    /**
     * Takes the raw data scraped from the data source,
     * and transforms it to suitable model.
     *
     * @param Collection $rawData
     * @return Collection
     */
    public function transformToModel(Collection $rawData) : Collection;
    
    /**
     *
     *
     * @param Collection $models
     * @param array $options
     * @return bool
     */
    public function save(Collection $models, $options = []) : bool;
}