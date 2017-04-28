<?php

namespace App\Services;

use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Exceptions\FacebookServerException;
use Facebook\Facebook as BaseFacebook;

class Facebook extends BaseFacebook
{
    /**
     * Fetches all of the results from an endpoint.
     * Does it for every set, if the response is paginated.
     *
     * @param $method
     * @param $endpoint
     * @param array $params
     * @return \Illuminate\Support\Collection
     */
    public function fetchAll($method, $endpoint, array $params)
    {
        $results = collect([]);
        
        do {
            // Send the request through actual Facebook SDK,
            // and get the JSON decoded response body.
            try {
                $response = $this->sendRequest($method, $endpoint, $params)->getDecodedBody();
            } catch (\Exception $e) {
                // Log the error.
                // And sleep for 2 seconds, just to make sure :P
                \Log::error($e);
                sleep(2);
                
                $response = ['data' => []];
            }
            
            // Merge the new fetched data with the previous data.
            $results = $results->merge($response['data']);
        } while(($params['after'] = $this->hasNext($response)));
        
        return $results;
    }
    
    /**
     * Fetches all of the events for the given place.
     *
     * @param $facebookId
     * @param $options
     * @return \Illuminate\Support\Collection
     */
    public function fetchAllEventsForPlace($facebookId, $options)
    {
        return $this->fetchAll('GET', $facebookId . '/events', $options);
    }
    
    /**
     * Determine if a response has paginated results.
     *
     * @param $response
     * @return bool
     */
    private function hasNext($response)
    {
        if (array_key_exists('paging', $response)
         && array_key_exists('next', $response['paging'])) {
            return $response['paging']['cursors']['after'];
        }
        
        return false;
    }
}