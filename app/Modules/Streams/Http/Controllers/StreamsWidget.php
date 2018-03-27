<?php 

namespace App\Modules\Streams\Http\Controllers;

use App\Modules\Streams\Stream;
use View;
use Widget;

class StreamsWidget extends Widget
{

    public function render(array $parameters = array())
    {
        $limit = isset($parameters['limit']) ? (int) $parameters['limit'] : self::LIMIT;

        $streams = Stream::orderBy('online', 'DESC')->orderBy('viewers', 'DESC')->take($limit)->get();

        return View::make('streams::widget', compact('streams'))->render();
    }

}