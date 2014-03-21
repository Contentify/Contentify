<?php namespace App\Modules\Auth\Controllers;

use View, Sentry, Input, Session, Redirect, Exception, FrontController;

class LoginController extends FrontController {
    
    public function getIndex()
    {
        $this->pageView('auth::login');
    }

    public function postIndex()
    {
        $credentials = [
            'email'     => Input::get('email'),
            'password'  => Input::get('password')
        ];

        try {
            $user = Sentry::authenticate($credentials, false); // login the user (if possible)

            if (Session::get('redirect')) {
                $redirect = Redirect::to(Session::get('redirect'));
                Session::forget('redirect');
                return $redirect;
            } else {
                return Redirect::to('/');   
            }
        } catch(Exception $e) {
            return Redirect::to('auth/login')->withErrors(['message' => $e->getMessage()]);
        }
    }
}