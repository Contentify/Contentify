<?php namespace App\Modules\Events\Http\Controllers;

use App\Modules\Events\Event;
use View, Widget;

class EventsWidget extends Widget {

    public function render($parameters = array())
    {
        $events = Event::orderBy('created_at', 'DESC')->take(5)->get();

        return View::make('events::widget', compact('events'))->render();
    }

}