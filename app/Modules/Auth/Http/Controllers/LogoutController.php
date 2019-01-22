<?php

namespace App\Modules\Auth\Http\Controllers;

use App\Modules\Auth\AuthManager;
use FrontController;

class LogoutController extends FrontController
{

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function getIndex()
    {
        $authManager = new AuthManager();
        $authManager->logoutUser();

        $this->alertFlash(trans('auth::logged_out'));

        return redirect('/');
    }
}
