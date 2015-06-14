<?php namespace App\Modules\Streams\Http\Controllers;

use App\Modules\Streams\Stream;
use View, Widget;

class StreamsWidget extends Widget {

    public function render($parameters = array())
    {
        $streams = Stream::orderBy('online', 'DESC')->orderBy('viewers', 'DESC')->take(5)->get();

        return View::make('streams::widget_streams', compact('streams'))->render();
    }

}