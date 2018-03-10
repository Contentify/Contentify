<?php

namespace App\Modules\Events\Http\Controllers;

use App\Modules\Events\Event;
use View, Widget;

class EventsWidget extends Widget
{

    public function render(array $parameters = array())
    {
        $limit = isset($parameters['limit']) ? (int) $parameters['limit'] : self::LIMIT;

        $events = Event::orderBy('created_at', 'DESC')->take($limit)->get();

        return View::make('events::widget', compact('events'))->render();
    }

}