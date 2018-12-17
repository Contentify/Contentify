<?php

namespace App\Modules\Games\Http\Controllers;

use App\Modules\Games\Game;
use BackController;
use Hover;
use HTML;
use ModelHandlerTrait;

class AdminGamesController extends BackController
{

    use ModelHandlerTrait;

    protected $icon = 'gamepad';

    public function __construct()
    {
        $this->modelClass = Game::class;

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
            'tableRow' => function(Game $game)
            {
                return [
                    $game->id,
                    raw($game->icon 
                        ? HTML::image($game->uploadPath().$game->icon, $game->title, ['width' => 16, 'height' => 16]) 
                        : NULL),
                    raw(Hover::modelAttributes($game, ['creator', 'updated_at'])->pull(), $game->title),
                ];
            }
        ]);
    }

}
