<?php

namespace App\Modules\Auth\Http\Controllers;

use View;
use Widget;

class LoginWidget extends Widget
{

    public function render(array $parameters = [])
    {
        if (user()) {
            return View::make('auth::widget_logged_in');
        } else {
            return View::make('auth::widget_login');
        }
    }

}
