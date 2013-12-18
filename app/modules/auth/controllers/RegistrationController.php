<?php namespace App\Modules\Auth\Controllers;

use View, Sentry, Input, Redirect, Session;

class RegistrationController extends \FrontController {
	public function index()
	{
		return View::make('auth::register');
	}

	public function register()
	{
		try {
			$user = Sentry::register(array(
				'email'		=> Input::get('email'),
				'password'	=> Input::get('password')
			), true);

			return View::make('auth::register_success');
		} catch(\Exception $e) {
			return Redirect::to('auth/registration')->withInput()->with('errors', $e->getMessage());
		}
	}
}