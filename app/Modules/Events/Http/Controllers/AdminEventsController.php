<?php

namespace App\Modules\Events\Http\Controllers;

use App\Modules\Events\Event;
use BackController;
use Hover;
use HTML;
use ModelHandlerTrait;

class AdminEventsController extends BackController
{

    use ModelHandlerTrait;

    protected $icon = 'map-marker';

    public function __construct()
    {
        $this->modelClass = Event::class;

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'sortby'    => 'starts_at',
            'tableHead' => [
                trans('app.id')         => 'id', 
                trans('app.title')      => 'title',
                trans('app.starts_at')  => 'starts_at',
            ],
            'tableRow' => function(Event $event)
            {
                return [
                    $event->id,
                    raw(Hover::modelAttributes($event, ['creator', 'updated_at'])->pull()
                        .HTML::link('events/'.$event->id.'/'.$event->slug,$event->title)),
                    $event->starts_at,
                ];
            }
        ]);
    }

}
