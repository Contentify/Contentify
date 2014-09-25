<?php namespace App\Modules\Videos\Controllers;

use App\Modules\Videos\Models\Video;
use DB, View, Widget;

class VideosWidget extends Widget {

    public function render($parameters = array())
    {
        if (isset($parameters['latest']) and $parameters['latest']) {
            $video = Video::orderBy('created_at', 'DESC')->first();
        } else {
            $video = Video::orderBy(DB::raw('RAND()'))->first();
        }        

        if ($video) {
            return View::make('videos::widget', compact('video'))->render();
        }
    }

}