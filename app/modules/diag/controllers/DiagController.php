<?php namespace App\Modules\Diag\Controllers;

use App, Config, View;

class DiagController extends \BackController {
	public function index()
	{
		$settings = array(
			'App.environment'	=> App::environment(),
			'App.url'			=> Config::get('app.url'),
			'App.debug' 		=> Config::get('app.debug'),
			'App.key'			=> (Config::get('app.key') == '01234567890123456789012345678912' ? 'Dummy' : 'Updated'),
			'Modules.mode' 		=> Config::get('modules::mode')
		);
	
		return View::make('diag::index', array('settings' => $settings));
	}
}