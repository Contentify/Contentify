<?php

namespace App\Modules\Polls\Http\Controllers;

use App\Modules\Polls\Poll;
use BackController;
use Hover;
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
                trans('app.id')     => 'id',
                trans('app.title')  => 'title'
            ],
            'tableRow' => function(Poll $poll)
            {
                return [
                    $poll->id,
                    raw(Hover::modelAttributes($poll, ['creator'])->pull(), $poll->title),
                ];
            }
        ]);
    }

}