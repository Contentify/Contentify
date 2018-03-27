<?php 

namespace App\Modules\Streams\Api;

use App\Modules\Streams\Stream;
use Config;
use DateTime;
use Log;

/**
 * @see https://dev.twitch.tv/docs/api
 */
class TwitchApi extends StreamApi
{

    /**
     * Identifier of the provider
     */
    const PROVIDER = 'twitch';

    /**
     * API endpoint URL
     */
    const URL = 'https://api.twitch.tv/kraken/';

    /**
     * Channel list API call
     */
    const CHANNEL_LIST = 'streams?channel=';

    /**
    * API key query parameter
    */
    const API_KEY_QUERY = '&client_id=';
   
    /**
     * Returns a JSON object that also includes an array of stream info
     * 
     * @param Stream[] $streams Array with objects of type Stream
     * @return \stdClass
     */
    public function getStreams(array $streams)
    {
        $apiKey = Config::get('app.twitchKey');

        $list = '';
        foreach ($streams as $stream) {
            if ($list) {
                $list .= ',';
            }
            $list .= $stream->permanent_id;
        }

        $response = $this->apiCall(self::URL.self::CHANNEL_LIST.$list.self::API_KEY_QUERY.$apiKey);
        
        return $response;
    }

    /**
     * Updates the passed streams
     * 
     * @param Stream[] $streams Array with objects of type Stream
     * @return void
     */
    public function updateStreams(array $streams)
    {
        $data = $this->getStreams($streams);

        if (isset($data->error)) {
            Log::error('Twitch API error: '.$data->error.' - '.$data->message);
            return;
        }

        foreach ($streams as $stream) {
            $stream->online  = false;
            $stream->viewers = 0;

            foreach ($data->streams as $streamInfo) {
                if ($streamInfo->channel->name == $stream->permanent_id) {
                    $stream->online    = true;
                    $stream->viewers   = $streamInfo->viewers;
                    $stream->thumbnail = $streamInfo->preview->medium;
                    break;
                }
            }

            $stream->renewed_at = new DateTime();
            $stream->forceSave();
        }
    }

}