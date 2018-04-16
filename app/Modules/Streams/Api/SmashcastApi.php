<?php 

namespace App\Modules\Streams\Api;

use App\Modules\Streams\Stream;
use DateTime;

/**
 * Note: Hitbox was acquired in 2017 and then became Smashcast.
 * This SmashcastApi class replaces the old HitboxApi class.
 *
 * @see https://developers.smashcast.tv/
 */
class SmashcastApi extends StreamApi
{

    /**
     * Identifier of the provider
     */
    const PROVIDER = 'smashcast';

    /**
     * API endpoint URL - Docs: https://developers.smashcast.tv/
     */
    const URL = 'https://api.smashcast.tv/';

    /**
     * Stream (=media) info API call - Docs: https://developers.smashcast.tv/#live
     */
    const MEDIA_LIST = 'media/live/';

    /**
     * Prefix URL of the media server (for thumbnails)
     * Note: Yep, this still is a Hitbox URL.
     */
    const MEDIA_URL = 'https://edge.sf.hitbox.tv';

    /**
     * Returns a JSON object that also includes an array of stream infos
     *
     * @param Stream[] $streams Array with objects of type Stream
     * @return \stdClass
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
     * @param Stream[] $streams Array with objects of type Stream
     * @return void
     */
    public function updateStreams(array $streams)
    {
        $data = $this->getStreams($streams);

        foreach ($streams as $stream) {
            $stream->online  = false;
            $stream->viewers = 0;

            foreach ($data->livestream as $streamInfo) {
                if ($streamInfo->media_name == $stream->permanent_id) {
                    $stream->online    = $streamInfo->media_is_live;
                    $stream->viewers   = $streamInfo->media_views;
                    $stream->thumbnail = self::MEDIA_URL.$streamInfo->media_thumbnail;
                    break;
                }
            }

            $stream->renewed_at = new DateTime();
            $stream->forceSave();
        }
    }

}