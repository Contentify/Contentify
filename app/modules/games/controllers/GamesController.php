<?php namespace App\Modules\Games\Controllers;

use App\Modules\Games\Models\Game as Game;

class GamesController extends \BackController {

	public function __construct()
	{
		$this->form['model'] = 'Game';

		parent::__construct();
	}


}