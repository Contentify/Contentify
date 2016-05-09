<?php namespace App\Modules\Users\Http\Controllers;

use DB, Cache, User, View, Widget;

class RandomUserWidget extends Widget {

    public function render($parameters = array())
    {
        $minutes = isset($parameters['minutes']) ? (int) $parameters['minutes'] : 10;

        $key = 'users::random_cached';
        $view = Cache::get($key);

        if ($view === null) {
            $user = User::orderBy(DB::raw('RAND()'))->first();

            $view = View::make('users::widget_random_user', compact('user'))->render();

            Cache::put($key, $view, $minutes * 60);

            return $view;
        }

        return $view;        
    }

}