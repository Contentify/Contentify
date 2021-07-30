<?php

namespace App\Modules\Auth\Http\Controllers;

use App;
use App\Modules\Auth\AuthManager;
use App\Modules\Languages\Language;
use Captcha;
use Exception;
use FrontController;
use Redirect;
use Request;
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
     * @throws Exception
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
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function postCreate()
    {
        try {
            /*
             * Validation
             */
            $rules = [
                'username'  => 'alpha_numeric_spaces|required|min:3|not_in:edit,password|unique:users,username',
                'email'     => 'email|unique:users,email|email_domain_allowed',
                'password'  => 'required|min:6|confirmed'
            ];

            $validator = Validator::make(Request::all(), $rules);
            if ($validator->fails()) {
                return Redirect::to('auth/registration/create')->withInput()->withErrors($validator);
            }

            if (! Captcha::check(Request::get('captcha'))) {
                return Redirect::to('auth/registration/create')
                    ->withInput()->withErrors(['message' => trans('app.captcha_invalid')]);
            }

            $language = Language::whereCode(App::getLocale())->first();

            /*
             * Register user
             */
            $user = Sentinel::register([
                'username'      => Request::get('username'),
                'email'         => Request::get('email'),
                'password'      => Request::get('password'),
                'language_id'   => $language->id,
            ], self::AUTO_ACTIVATE);

            /*
             * Add user to role "Users"
             * This role is a basic role that isn't deletable so we do know it exists.
             * (If it doesn't exist, we have a serious problem.)
             */
            $userRole = Sentinel::findRoleBySlug('users');
            $userRole->users()->attach($user);

            event(AuthManager::EVENT_NAME_USER_REGISTERED, [$user]);

            /*
             * Login user
             */
            Sentinel::login($user, false);
            event(AuthManager::EVENT_NAME_USER_LOGGED_IN, [$user]);

            $this->alertSuccess(trans('auth::registered'));
        } catch (Exception $e) {
            return Redirect::to('auth/registration/create')->withInput()->withErrors(['message' => $e->getMessage()]);
        }
    }

    /**
     * Check (typically via AJAX call) if a username is already taken
     *
     * @param string $username
     * @return \Illuminate\Http\Response
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
