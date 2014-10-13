<?php namespace App\Modules\Users\Controllers;

use User, View, Widget;

class LatestUsersWidget extends Widget {

    public function render($parameters = array())
    {
        $users = User::orderBy('created_at', 'DESC')->take(5)->get();

        return View::make('users::widget_latest_users', compact('users'))->render();
    }

}