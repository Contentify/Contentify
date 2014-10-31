<?php namespace App\Modules\Streams\Models;

use Job;

class UpdateStreamsJob extends Job {

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
            }
        }
    }

}