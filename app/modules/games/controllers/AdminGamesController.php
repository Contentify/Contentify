<?php namespace App\Modules\Games\Controllers;

use App\Modules\Games\Models\Game as Game;
use BackController;

class AdminGamesController extends BackController {

    protected $icon = 'joystick.png';

	public function __construct()
	{
		$this->model = 'Game';

		parent::__construct();
	}

    public function index()
    {
        $this->buildIndexForm([
            'tableHead' => [
                t('ID')     => 'id', 
                t('Title')  => 'title'
            ],
            'tableRow' => function($game)
            {
                return [
                    $game->id,
                    $game->title
                ];
            }
        ]);
    }

}