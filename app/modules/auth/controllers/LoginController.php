<?php namespace App\Modules\Auth\Controllers;

use View, Sentry, Input, Session, Redirect;

class LoginController extends \FrontController {
	
	public function index()
	{
		$this->pageView('auth::login');
	}

	public function login()
	{
		$credentials = array(
			'email'		=> Input::get('email'),
			'password'	=> Input::get('password')
		);

		try {
			$user = Sentry::authenticate($credentials, false); // login the user (if possible)

			return Redirect::to('/');
		} catch(\Exception $e) {
			return Redirect::to('auth')->with('errors', $e->getMessage());
		}
	}
}