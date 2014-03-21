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
        $this->buildIndexPage([
            'tableHead' => [
                t('ID')     => 'id', 
                t('Title')  => 'title'
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