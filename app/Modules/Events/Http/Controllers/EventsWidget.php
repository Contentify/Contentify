<?php

namespace App\Modules\Events\Http\Controllers;

use App\Modules\Events\Event;
use View;
use Widget;

class EventsWidget extends Widget
{

    public function render(array $parameters = array())
    {
        $limit = isset($parameters['limit']) ? (int) $parameters['limit'] : self::LIMIT;
        $hasAccess = (user() and user()->hasAccess('internal'));

        $query = Event::orderBy('starts_at', 'DESC');
        if (! $hasAccess) {
            $query->whereInternal(false);
        }
        $events = $query->take($limit)->get();

        return View::make('events::widget', compact('events'))->render();
    }

}
