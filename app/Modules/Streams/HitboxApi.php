<?php namespace App\Modules\Streams;

use DateTime;

class HitboxApi extends StreamApi
{

    /**
     * API URL - Docs: http://developers.hitbox.tv/start
     */
    const URL = 'http://api.hitbox.tv/';

    /**
     * Stream (=media) info API call - Docs: http://developers.hitbox.tv/media
     */
    const MEDIA_LIST = 'media/live/';

    /**
     * Prefix URL of the media server (for thumbnails)
     */
    const MEDIA_URL = 'http://edge.vie.hitbox.tv';
    
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

        $response = $this->apiCall(self::URL.self::MEDIA_LIST.$list);
        
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

            foreach ($data->livestream as $streamInfo) {
                if ($streamInfo->media_name == $stream->permanent_id) {
                    $stream->online     = $streamInfo->media_is_live;
                    $stream->viewers    = $streamInfo->media_views;
                    $stream->thumbnail  = self::MEDIA_URL.$streamInfo->media_thumbnail;
                    break;
                }
            }

            $stream->renewed_at = new DateTime();
            $stream->forceSave();
        }
    }

}