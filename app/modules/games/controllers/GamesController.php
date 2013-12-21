<?php namespace App\Modules\Games\Controllers;

use App\Modules\Games\Models\Game as Game;
use App, Config, View, Input, Redirect;

class GamesController extends \BackController {

	public function index()
	{
		//die('<a href="'.route('admin.games.destroy', 17).'?method=delete">Click me!</a>');

		$this->message('Index called!');
	}

	public function create()
	{
		$this->pageView('games::form');
	}

	public function store()
	{
		$game = new Game();

		$game->title = Input::get('title');
		$game->tag = Input::get('tag');
		$game->save();

		return Redirect::route('admin.games.index');
	}

	public function edit($id)
	{
		$game = Game::findOrFail($id);

		$this->pageView('games::form', array('game' => $game));
	}

	public function update($id)
	{
		$game = Game::findOrFail($id);

		$game->title = Input::get('title');
		$game->tag = Input::get('tag');
		$game->save();

		return Redirect::route('admin.games.index');
	}

	public function destroy($id)
	{
		Game::destroy($id);

		return Redirect::route('admin.games.index');
	}

}