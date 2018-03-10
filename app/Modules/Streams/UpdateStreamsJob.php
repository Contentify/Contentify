<?php 

namespace App\Modules\Streams;

use Job;

class UpdateStreamsJob extends Job 
{

    protected $timeSpan = 5; // Minutes

    public function run($executed)
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
                case 'twitch':
                    $twitchApi  = new TwitchApi();
                    $twitchApi->updateStreams($streams);

                    break;
                case 'hitbox':
                    $hitboxApi  = new HitboxApi();
                    $hitboxApi->updateStreams($streams);

                    break;
                case 'smashcast':
                    $smashcastApi  = new SmashcastApi();
                    $smashcastApi->updateStreams($streams);

                    break;
            }
        }
    }

}