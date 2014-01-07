<?php namespace App\Modules\Diag\Controllers;

use App, Config, View, BackController;

class AdminDiagController extends BackController {

	protected $icon = 'information.png';
	
	public function getIndex()
	{
		$settings = array(
			'App.environment'	=> App::environment(),
			'App.url'			=> Config::get('app.url'),
			'App.debug' 		=> (int) Config::get('app.debug'),
			'App.key'			=> (Config::get('app.key') == '01234567890123456789012345678912' ? 'Dummy' : 'Updated'),
			'Modules.mode' 		=> Config::get('modules::mode'),
			'Modules.debug' 	=> (int) Config::get('modules::debug')
		);
	
		$this->pageView('diag::index', compact('settings'));
	}
}