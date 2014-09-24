<?php namespace App\Modules\Games\Controllers;

use App\Modules\Games\Models\Game;
use Hover, HTML, BackController;

class AdminGamesController extends BackController {

    protected $icon = 'controller.png';

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
                return [
                    $game->id,
                    $game->icon 
                        ? HTML::image($game->uploadPath().$game->icon, $game->title, ['width' => 16, 'height' => 16]) 
                        : NULL,
                    Hover::modelAttributes($game, ['creator'])->pull().$game->title,
                ];            
            }
        ]);
    }

}