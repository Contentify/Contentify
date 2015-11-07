<?php namespace App\Modules\Auth\Http\Controllers;

use App\Modules\Languages\Language;
use Response, User, App, Lang, Str, Sentry, Input, Redirect, Session, Captcha, FrontController, Exception, Validator;

class RegistrationController extends FrontController {

    /**
     * If set to true, user that register will be
     * activated automatically. If set to false,
     * an admin has to activate them manually.
     */
    const AUTO_ACTIVATE = true;

    public function getCreate()
    {
        if (user()) {
            $this->alertSuccess(trans('auth::register_twice'));    
            return;
        }        

        $this->pageView('auth::register');
    }

    public function postCreate()
    {
        try {
            /*
             * Validation
             */
            $rules = array(
                'username'  => 'alpha_numeric_spaces|required|min:3|not_in:edit,password|unique:users,username', 
                'email'     => 'email|unique:users,email', 
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
             * Register user.
             */
            $user = Sentry::register([
                'username'      => Input::get('username'),
                'email'         => Input::get('email'),
                'password'      => Input::get('password'),
                'language_id'   => $language->id,
            ], self::AUTO_ACTIVATE);

            $user->slug = Str::slug($user->username);
            $user->save();

            /*
             * Add user to group "Users"
             * This group is a basic group that isn't deletable so we do know it exists.
             * (If it does'nt exist, we have a serious problem.)
             */
            $userGroup = Sentry::findGroupById(2);
            $user->addGroup($userGroup);

            /*
             * Login user
             */
            Sentry::login($user, false);

            $this->alertSuccess(trans('auth::registered'));
        } catch(Exception $e) {
            return Redirect::to('auth/registration/create')->withInput()->withErrors(['message' => $e->getMessage()]);
        }
    }

    /**
     * Check if a username is already taken
     * 
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