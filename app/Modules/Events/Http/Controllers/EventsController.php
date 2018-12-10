<?php

namespace App\Modules\Events\Http\Controllers;

use App\Modules\Events\Event;
use Contentify\GlobalSearchInterface;
use FrontController;
use Request;
use URL;

class EventsController extends FrontController implements GlobalSearchInterface
{

    public function __construct()
    {
        $this->modelClass = Event::class;

        parent::__construct();
    }

    public function index()
    {
        $hasAccess = (user() and user()->hasAccess('internal'));

        $query = Event::orderBy('starts_at', 'DESC');
        if (! $hasAccess) {
            $query->whereInternal(false);
        }
        $events = $query->get();

        $this->pageView('events::index', compact('events'));
    }

    /**
     * Show an event
     * 
     * @param  int $id The ID of the event
     * @return void
     */
    public function show($id)
    {
        /** @var Event $event */
        $event = Event::findOrFail($id);

        $hasAccess = (user() and user()->hasAccess('internal'));
        if ($event->internal and ! $hasAccess) {
            $this->alertError(trans('app.access_denied'));
            return;
        }

        $event->access_counter++;
        $event->save();

        $this->title($event->title);

        $this->pageView('events::show', compact('event'));
    }

    /**
     * Show calendar with all events
     *
     * @param int $year  The year to show
     * @param int $month The month of the year to show
     * @return null|string
     */
    public function calendar($year = null, $month = null)
    {
        if (Request::ajax()) {
            $widget = new CalendarWidget;
            return $widget->render(compact('year', 'month'));
        } else {
            $this->title(trans('app.calendar'));
            $this->pageView('events::calendar', compact('year', 'month'));
        }
    }
    
    /**
     * This method is called by the global search (SearchController->postCreate()).
     * Its purpose is to return an array with results for a specific search query.
     * 
     * @param  string $subject The search term
     * @return string[]
     */
    public function globalSearch($subject)
    {
        $events = Event::where('title', 'LIKE', '%'.$subject.'%')->get();

        $results = array();
        foreach ($events as $event) {
            $results[$event->title] = URL::to('events/'.$event->id.'/'.$event->slug);
        }

        return $results;
    }

}
