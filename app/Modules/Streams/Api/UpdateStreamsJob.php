<?php 

namespace App\Modules\Streams\Api;

use AbstractJob;
use App\Modules\Streams\Stream;

class UpdateStreamsJob extends AbstractJob
{

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
            }
        }
    }

}