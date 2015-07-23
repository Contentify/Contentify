<?php namespace App\Modules\Users\Http\Controllers;

use User, View, Widget;

class OnlineWidget extends Widget {

    public function render($parameters = array())
    {
        $users = User::online()->orderBy('last_active', 'DESC')->take(5)->get();

        return View::make('users::widget_online', compact('users'))->render();
    }

}