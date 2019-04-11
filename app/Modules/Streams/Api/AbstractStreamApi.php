<?php 

namespace App\Modules\Streams\Api;

use Log;

abstract class AbstractStreamApi
{
    
    /**
     * Updates the meta information of the passed streams in the database.
     * This method to be implemented in the concrete class.
     * 
     * @param Stream[] $streams Array with objects of type Stream
     * @return void
     */
    abstract public function updateStreams(array $streams);

    /**
     * Makes the API call
     * 
     * @param string  $url   The API URL
     * @param boolean $parse Parse the result?
     * @return mixed
     */
    public function apiCall(string $url, bool $parse = true)
    {
        $curl = curl_init();

        // Do not try to verify the SSL certificate - it could fail
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_URL, $url);

        $result = curl_exec($curl);

        if ($result === false) {
            Log::warning('Streams job: cURL error: '.curl_error($curl));
            return null;
        }

        if ($parse) {
            return $this->parseResponse($result);
        }
        return $result;
    }
    
    /**
     * Parses the response
     *
     * @param  string $response
     * @return mixed
     */
    public function parseResponse(string $response)
    {
        return json_decode($response);
    }
}
