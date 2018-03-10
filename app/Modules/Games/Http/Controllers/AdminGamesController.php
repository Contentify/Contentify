<?php

namespace App\Modules\Games\Http\Controllers;

use App\Modules\Games\Game;
use ModelHandlerTrait;
use Hover, HTML, BackController;

class AdminGamesController extends BackController {

    use ModelHandlerTrait;

    protected $icon = 'gamepad';

    public function __construct()
    {
        $this->modelName = 'Game';

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
            'tableRow' => function($game)
            {
                /** @var Game $game */
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