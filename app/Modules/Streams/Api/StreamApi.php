<?php 

namespace App\Modules\Streams\Api;

use Log;

class StreamApi 
{

    /**
     * Makes the API call
     * 
     * @param string  $url   The API URL
     * @param boolean $parse Parse the result?
     * @return mixed
     */
    public function apiCall($url, $parse = true)
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
    public function parseResponse($response)
    {
        return json_decode($response);
    }

}