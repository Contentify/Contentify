<?php

namespace App\Modules\Auth\Http\Controllers;

use App;
use App\Modules\Auth\AuthManager;
use App\Modules\Languages\Language;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Config;
use Exception;
use FrontController;
use Illuminate\Http\RedirectResponse;
use Invisnik\LaravelSteamAuth\SteamAuth;
use Redirect;
use Request;
use Sentinel;
use Session;
use Str;
use User;
use Validator;

class LoginController extends FrontController
{

    public function getLogin()
    {
        $this->pageView('auth::login');
    }

    /**
     * Login with email and password
     *
     * @return RedirectResponse
     */
    public function postLogin()
    {
        $credentials = [
            'email'     => Request::get('email'),
            'password'  => Request::get('password')
        ];

        $authManager = new AuthManager();

        try {
            $user = $authManager->loginUserByEmail($credentials);
        } catch (ThrottlingException $exception) {
            $this->alertFlash($exception->getMessage());
            return Redirect::to('auth/login');
        }

        if ($user) {
            return $this->afterLoginActions();
        } else {
            $this->alertFlash(trans('app.access_denied'));
            return Redirect::to('auth/login');
        }
    }

    /**
     * Login with STEAM account
     *
     * @param SteamAuth $steam
     * @return RedirectResponse|null
     * @throws Exception
     */
    public function getSteam(SteamAuth $steam)
    {
        $apiKey = Config::get('steam-auth.api_key');

        if (! $apiKey) {
            // Throw an exception if no API key has been set. if we do not throw this exception
            // the API call will fail but return a vague error message, so better ensure clarity.
            throw new Exception('Error: No API key set in the steam-auth config file. Please set it!');
        }

        if ($steam->validate()) {
            $info = $steam->getUserInfo();

            if ($info !== null) {
                /** @var User $user */
                $user = User::where('steam_auth_id', $info->steamID64)->first();

                if ($user !== null) { // Login into existing user account
                    if ($user->banned) {
                        $this->alertFlash(trans('app.access_denied'));
                        return Redirect::to('auth/login');
                    }

                    Sentinel::loginAndRemember($user);
                    event(AuthManager::EVENT_NAME_USER_LOGGED_IN, [$user]);

                    return $this->afterLoginActions();
                } else { // Display a form where the user has to set his username and email address
                    $username = $info->personaname;

                    /*
                     * The username has to be unique so ensure that it is
                     */
                    $i = 1;
                    while ($user = User::whereUsername($username)->first()) {
                        $username = $info->personaname.($i++);
                    }

                    // Store the ID in the (server side) session so it cannot be manipulated by the client
                    Session::put('steamId', $info->steamID64);

                    $this->pageView('auth::register_steam', compact('username'));
                    return null;
                }
            }
        } else {
            return $steam->redirect(); // Redirect to Steam login page
        }
    }

    /**
     * Actually create the user account with a STEAM ID
     *
     * @return RedirectResponse|null
     * @throws Exception
     */
    public function postSteam() : ?RedirectResponse
    {
        $steamId = Session::get('steamId');

        /*
         * Validation
         */
        $rules = [
            'username'  => 'alpha_numeric_spaces|required|min:3|not_in:edit,password|unique:users,username',
            'email'     => 'email|unique:users,email'
        ];

        $validator = Validator::make(Request::all(), $rules);
        if ($validator->fails()) {
            $username = Request::get('username');
            $errors = $validator->errors();

            $this->pageView('auth::register_steam', compact('username', 'steamId', 'errors'));
            return null;
        }

        // Not sure if this can happen but better safe than sorry
        if ($steamId === null) {
            $this->alertFlash(trans('app.access_denied'));
            return Redirect::to('auth/login');
        }

        $language = Language::whereCode(App::getLocale())->first();

        /*
         * Register user.
         */
        /** @var User $user */
        $user = Sentinel::register([
            'username'      => Request::get('username'),
            'email'         => Request::get('email'),
            'password'      => Str::random(), // So no one can login without Steam auth
            'language_id'   => $language->id,
        ], true); // Auto activate the user

        $user->steam_auth_id = $steamId;
        $user->save();

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
        Sentinel::loginAndRemember($user);
        event(AuthManager::EVENT_NAME_USER_LOGGED_IN, [$user]);

        Session::forget('steamId');

        $this->alertFlash(trans('auth::steam_registered'));
        return Redirect::to('/');
    }

    /**
     * Executes additional actions after user login
     *
     * @return RedirectResponse
     */
    public function afterLoginActions() : RedirectResponse
    {
        Session::put('app.locale', user()->language->code); // Set session locale to account language

        if (Session::get('redirect')) {
            $redirect = Redirect::to(Session::get('redirect'));
            Session::forget('redirect');
            return $redirect;
        } else {
            return Redirect::to('/');
        }
    }
}
