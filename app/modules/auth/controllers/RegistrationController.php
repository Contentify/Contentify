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
				return Redirect::to('auth/registration/create')->withInput()->withErrors(['message' => t('The passwords have to match!')]);
			}

			/*
			 * Register user
			 */
			$user = Sentry::register(array(
				'username'	=> Input::get('username'),
				'email'		=> Input::get('email'),
				'password'	=> Input::get('password'),
			), true);

			/*
			 * Add user to group "Users"
			 * This group is a basic group so we do know it exists.
			 * (If it does not exist, we have a serious problem.)
			 */
			$userGroup = Sentry::findGroupById(2);
			$user->addGroup($userGroup);

			$this->message(t('Registration successful!'));
		} catch(\Exception $e) {
			return Redirect::to('auth/registration/create')->withInput()->withErrors(['message' => $e->getMessage()]);
		}
	}
}