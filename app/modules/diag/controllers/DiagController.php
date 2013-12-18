<?php namespace App\Modules\Diag\Controllers;

use App, Config, View;

class DiagController extends \BaseController {
	public function index()
	{
		$settings = array(
			'App.environmnet'	=> App::environment(),
			'App.url'			=> Config::get('app.url'),
			'App.debug' 		=> Config::get('app.debug'),
			'Modules.mode' 		=> Config::get('modules::mode')
		);
	
		return View::make('diag::index', array('settings' => $settings));
	}
}