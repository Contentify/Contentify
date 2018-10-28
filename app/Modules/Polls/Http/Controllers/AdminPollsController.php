<?php

namespace App\Modules\Games\Http\Controllers;

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
                trans('app.id')     => 'id', 
                trans('app.icon')   => NULL,
                trans('app.title')  => 'title'
            ],
            'tableRow' => function(Poll $game)
            {
                return [
                    $game->id,
                    raw($game->icon 
                        ? HTML::image($game->uploadPath().$game->icon, $game->title, ['width' => 16, 'height' => 16]) 
                        : NULL),
                    raw(Hover::modelAttributes($game, ['creator'])->pull(), $game->title),
                ];
            }
        ]);
    }

}