<?php

namespace App\Modules\Polls\Http\Controllers;

use App\Modules\Polls\Poll;
use BackController;
use Hover;
use HTML;
use ModelHandlerTrait;

class AdminPollsController extends BackController
{

    use ModelHandlerTrait;

    protected $icon = 'poll';

    public function __construct()
    {
        $this->modelClass = Poll::class;

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'tableHead' => [
                trans('app.id')         => 'id',
                trans('app.open')       => 'open',
                trans('app.title')      => 'title' ,
                trans('app.created_at') => 'created_at'
            ],
            'tableRow' => function(Poll $poll)
            {
                return [
                    $poll->id,
                    raw($poll->open ? HTML::fontIcon('check') : HTML::fontIcon('times')),
                    raw(Hover::modelAttributes($poll, ['creator', 'updated_at'])->pull(), $poll->title),
                    $poll->created_at
                ];
            }
        ]);
    }

}
