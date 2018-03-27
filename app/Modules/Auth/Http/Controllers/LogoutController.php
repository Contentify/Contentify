<?php

namespace App\Modules\Auth\Http\Controllers;

use FrontController;
use Sentinel;

class LogoutController extends FrontController
{

    public function getIndex()
    {
        Sentinel::logout();

        $this->alertFlash(trans('auth::logged_out'));

        return redirect('/');
    }
}