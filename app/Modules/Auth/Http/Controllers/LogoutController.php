<?php namespace App\Modules\Auth\Http\Controllers;

use View, Sentry, Input, Session, Redirect, FrontController;

class LogoutController extends FrontController {

    public function getIndex()
    {
        Sentry::logout();

        $this->alertInfo(trans('auth::logged_out'));
    }
}