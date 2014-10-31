<?php namespace App\Modules\Streams\Controllers;

use App\Modules\Streams\Models\Stream;
use View, Widget;

class StreamsWidget extends Widget {

    public function render($parameters = array())
    {
        $streams = Stream::orderBy('online', 'DESC')->orderBy('viewers', 'DESC')->take(5)->get();

        return View::make('streams::widget_streams', compact('streams'))->render();
    }

}