<?php namespace App\Modules\Games\Controllers;

use App\Modules\Games\Models\Game as Game;
use Hover, BackController;

class AdminGamesController extends BackController {

    protected $icon = 'joystick.png';

    public function __construct()
    {
        $this->model = 'Game';

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'tableHead' => [
                trans('app.id')     => 'id', 
                trans('app.title')  => 'title'
            ],
            'tableRow' => function($game)
            {
                $hover = new Hover();
                if ($game->icon) $hover->image(asset('uploads/games/'.$game->icon));

                return [
                    $game->id,
                    $hover.$game->title,
                ];            
            }
        ]);
    }

}