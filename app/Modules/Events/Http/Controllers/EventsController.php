<?php namespace App\Modules\Events\Http\Controllers;

use App\Modules\Events\Models\Event;
use FrontController;

class EventsController extends FrontController {

    public function __construct()
    {
        $this->modelName = 'Event';

        parent::__construct();
    }

    public function index()
    {
        $events = Event::orderBy('starts_at', 'DESC')->get();

        $this->pageView('events::index', compact('events'));
    }

    /**
     * Show an event
     * 
     * @param  int $id The id of the event
     * @return void
     */
    public function show($id)
    {
        $event = Event::findOrFail($id);

        $this->title($event->title);

        $this->pageView('events::show', compact('event'));
    }

}