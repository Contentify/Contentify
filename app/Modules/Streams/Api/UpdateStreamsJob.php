<?php 

namespace App\Modules\Streams\Api;

use AbstractJob;
use App\Modules\Streams\Stream;

/**
 * This jobs tries to update the meta information (current number of viewervs, etc.)
 * of all streams by retrieving them from the APIs of the stream providers.
 * This task can be slow so we have outsourced it to a job that can run in the background.
 */
class UpdateStreamsJob extends AbstractJob
{
    /**
     * Name of the event that is fired when streams have to be updated
     * that do not use one of the built-in providers
     */
    const EVENT_NAME_UPDATE_EXTRA_STREAMS = 'contentify.streams.updateStream';

    protected $interval = 5; // Minutes

    public function run($executedAt)
    {
        $streams = Stream::all();

        /*
         * Create a new array with streams sorted by provider
         */
        $streamsByProvider = [];
        foreach ($streams as $stream) {
            if (isset($stream->provider)) {
                $streamsByProvider[$stream->provider][$stream->permanent_id] = $stream;
            } else {
                // FIXME: We are in the else-part of "isset($stream->provider)". 
                // So if the provider is NOT set, whe use the provider (which will be null) as the key of the array?
                // This seems to be wrong...
                $streamsByProvider[$stream->provider] = [$stream->permanent_id => $stream];
            }
        }

        /*
         * Run trough the providers and handle their streams
         */
        foreach ($streamsByProvider as $provider => $streams) {
            switch ($provider) {
                case TwitchApi::PROVIDER:
                    $twitchApi  = new TwitchApi();
                    $twitchApi->updateStreams($streams);

                    break;
                case SmashcastApi::PROVIDER:
                    $smashcastApi  = new SmashcastApi();
                    $smashcastApi->updateStreams($streams);

                    break;
                case default;
                    event(self::EVENT_NAME_UPDATE_EXTRA_STREAMS, [$streams]);
            }
        }
    }
}
