<?php

namespace App\Modules\Auth\Http\Controllers;

use Invisnik\LaravelSteamAuth\SteamAuth;
use App\Modules\Languages\Language;
use App, User, Str, Sentinel, Input, Session, Config, Redirect, Exception, FrontController;

class LoginController extends FrontController {
    
    public function getLogin()
    {
        $this->pageView('auth::login');
    }

    public function postLogin()
    {
        $credentials = [
            'email'     => Input::get('email'),
            'password'  => Input::get('password')
        ];

        $user = Sentinel::authenticate($credentials, false); // Login the user (if possible)

        if ($user and $user->banned) {
            Sentinel::logout();
            $user = null;
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
     * @return Redirect
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

                if ($user !== null) {
                    if ($user->banned) {
                        $this->alertFlash(trans('app.access_denied'));
                        return Redirect::to('auth/login');
                    }

                    Sentinel::loginAndRemember($user);

                    return $this->afterLoginActions();
                } else {
                    $username = $info->personaname;

                    /*
                     * The username has to be unique so ensure that it is
                     */
                    $i = 1;
                    while ($user = User::whereUsername($username)->first()) {
                        $username = $info->personaname.($i++);
                    }

                    $language = Language::whereCode(App::getLocale())->first();

                    /*
                     * Register user.
                     */
                    $user = Sentinel::register([
                        'username'      => $username,
                        'email'         => 'contentify.org',
                        'password'      => Str::random(), // So no one can login without Steam auth
                        'language_id'   => $language->id,
                    ], true); // Auto activate the user

                    $user->createSlug(true, 'username');
                    $user->email = $info->steamID64.'@nomail.contentify.org'; // Email has to be unique and != null
                    $user->steam_auth_id = $info->steamID64;
                    $user->save();

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
                    Sentinel::loginAndRemember($user);
                    
                    $this->alertFlash(trans('auth::steam_registered'));
                    return Redirect::to('/users/'.$user->id.'/edit');
                }
            }
        } else {
            return $steam->redirect(); // Redirect to Steam login page
        }
    }

    /**
     * Executes addinional actions after user login
     * 
     * @return Redirect
     */
    public function afterLoginActions()
    {
        Session::set('app.locale', user()->language->code); // Set session locale to account language

        if (Session::get('redirect')) {
            $redirect = Redirect::to(Session::get('redirect'));
            Session::forget('redirect');
            return $redirect;
        } else {
            return Redirect::to('/');
        }
    }

}