<?php namespace App\Modules\Games\Controllers;

use App\Modules\Games\Models\Game as Game;

class AdminGamesController extends \BackController {

	public function __construct()
	{
		$this->form['model'] = 'Game';

		parent::__construct();
	}

    public function index()
    {
        $this->buildIndexForm(array(
            'tableHead' => [t('ID') => 'id', t('Title') => 'title'],
            'tableRow' => function($game)
            {
                return array(
                    $game->id,
                    $game->title
                    );
            }
            ));
    }

}