<?php

namespace App\Modules\Auth\Http\Controllers;

use App;
use App\Modules\Languages\Language;
use Captcha;
use Exception;
use FrontController;
use Input;
use Redirect;
use Response;
use Sentinel;
use User;
use Validator;

class RegistrationController extends FrontController
{

    /**
     * If set to true, users who register will be
     * activated automatically. If set to false,
     * an admin has to activate them manually.
     */
    const AUTO_ACTIVATE = true;

    /**
     * Show registration page
     *
     * @return void
     */
    public function getCreate()
    {
        if (user()) {
            $this->alertError(trans('auth::register_twice'));
            return;
        }

        $this->pageView('auth::register');
    }

    /**
     * Create new user account
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreate()
    {
        try {
            /*
             * Validation
             */
            $rules = array(
                'username'  => 'alpha_numeric_spaces|required|min:3|not_in:edit,password|unique:users,username', 
                'email'     => 'email|unique:users,email|email_domain_allowed',
                'password'  => 'required|min:6|confirmed'
            );

            $validator = Validator::make(Input::all(), $rules);
            if ($validator->fails()) {
                return Redirect::to('auth/registration/create')->withInput()->withErrors($validator);
            }

            if (! Captcha::check(Input::get('captcha'))) {
                return Redirect::to('auth/registration/create')
                    ->withInput()->withErrors(['message' => trans('app.captcha_invalid')]);
            }

            $language = Language::whereCode(App::getLocale())->first();

            /*
             * Register user
             */
            $user = Sentinel::register([
                'username'      => Input::get('username'),
                'email'         => Input::get('email'),
                'password'      => Input::get('password'),
                'language_id'   => $language->id,
            ], self::AUTO_ACTIVATE);

            /*
             * Add user to role "Users"
             * This role is a basic role that isn't deletable so we do know it exists.
             * (If it doesn't exist, we have a serious problem.)
             */
            $userRole = Sentinel::findRoleBySlug('users');
            $userRole->users()->attach($user);

            /*
             * Login user
             */
            Sentinel::login($user, false);

            $this->alertSuccess(trans('auth::registered'));
        } catch(Exception $e) {
            return Redirect::to('auth/registration/create')->withInput()->withErrors(['message' => $e->getMessage()]);
        }
    }

    /**
     * Check if a username is already taken
     *
     * @param string $username
     * @return Response
     */
    public function checkUsername($username)
    {
        $user = User::whereUsername($username)->first();

        if ($user) {
            return Response::make('1');
        }
        return Response::make('0');
    }

}