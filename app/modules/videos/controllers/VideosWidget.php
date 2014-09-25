<?php namespace App\Modules\Videos\Controllers;

use App\Modules\Videos\Models\Video;
use View, Widget;

class VideosWidget extends Widget {

    public function render($parameters = array())
    {
        $video = Video::orderBy('created_at', 'DESC')->first();

        if ($video) {
            return View::make('videos::widget', compact('video'))->render();
        }
    }

}