<?php

namespace App\Modules\Events\Http\Controllers;

use App\Modules\Events\Event;
use Carbon;
use View;
use Widget;

class CalendarWidget extends Widget
{

    public function render(array $parameters = array())
    {
        if (isset($parameters['year'])) {
            $year = $parameters['year'];
        } else {
            $year = date('Y');
        }

        if (isset($parameters['month'])) {
            $month = $parameters['month'];
        } else {
            $month = date('n');
        }

        $hasAccess = (user() and user()->hasAccess('internal'));

        $query = Event::eventsOfMonth($year, $month);
        if (! $hasAccess) {
            $query->whereInternal(false);
        }
        $events = $query->get();

        $firstOfMonth = new Carbon;
        $firstOfMonth->setDate($year, $month, 1)->setTime(0, 0, 0);

        $lastOfMonth = new Carbon;
        $lastOfMonth->setDate($year, $month, $firstOfMonth->daysInMonth)->setTime(0, 0, 0);

        $day = $firstOfMonth->copy()->startOfWeek();

        return View::make(
            'events::widget_calendar', 
            compact('events', 'year', 'month', 'day', 'firstOfMonth', 'lastOfMonth')
        )->render();
    }

}
