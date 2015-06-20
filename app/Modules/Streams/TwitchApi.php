<?php namespace App\Modules\Streams;

use DateTime;

class TwitchApi extends StreamApi
{

    /**
     * API URL
     */
    const URL = 'https://api.twitch.tv/kraken/';

    /**
     * Channel list API call
     */
    const CHANNEL_LIST = 'streams?channel=';
    
    /**
     * Returns a JSON object that also includes an array of stream infos
     * 
     * @param array $channel Array with objects of type Stream
     * @return array 
     */
    public function getStreams(array $streams)
    {
        $list = '';
        foreach ($streams as $stream) {
            if ($list) {
                $list .= ',';
            }
            $list .= $stream->permanent_id;
        }

        $response = $this->apiCall(self::URL.self::CHANNEL_LIST.$list);
        
        return $response;
    }

    /**
     * Updates the passed streams
     * 
     * @param  array $streams Array with objects of type Stream
     * @return void
     */
    public function updateStreams(array $streams)
    {
        $data = $this->getStreams($streams);

        foreach ($streams as $stream) {
            $stream->online     = false;
            $stream->viewers    = 0;

            foreach ($data->streams as $streamInfo) {
                if ($streamInfo->channel->name == $stream->permanent_id) {
                    $stream->online     = true;
                    $stream->viewers    = $streamInfo->viewers;
                    break;
                }
            }

            $stream->renewed_at = new DateTime();
            $stream->forceSave();
        }
    }

}