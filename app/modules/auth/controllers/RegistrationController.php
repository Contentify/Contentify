<?php namespace App\Modules\Auth\Controllers;

use View, Sentry, Input, Redirect, Session;

class RegistrationController extends \FrontController {
	public function index()
	{
		$this->pageView('auth::register');
	}

	public function register()
	{
		try {
			$user = Sentry::register(array(
				'email'		=> Input::get('email'),
				'password'	=> Input::get('password')
			), true);

			$this->pageView('auth::register_success');
		} catch(\Exception $e) {
			return Redirect::to('auth/registration')->withInput()->with('errors', $e->getMessage());
		}
	}
}