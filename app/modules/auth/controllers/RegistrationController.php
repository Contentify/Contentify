<?php namespace App\Modules\Auth\Controllers;

use View, Sentry, Input, Redirect, Session;

class RegistrationController extends \FrontController {

	public function getCreate()
	{
		$this->pageView('auth::register');
	}

	public function postCreate()
	{
		try {
			if (Input::get('password') != Input::get('password2')) {
				return Redirect::to('auth/registration')->withInput()->with('errors', t('The passwords have to match!'));
			}

			$user = Sentry::register(array(
				'email'		=> Input::get('email'),
				'password'	=> Input::get('password')
			), true);

			$this->message(t('Registration successful!'));
		} catch(\Exception $e) {
			return Redirect::to('auth/registration')->withInput()->with('errors', $e->getMessage());
		}
	}
}