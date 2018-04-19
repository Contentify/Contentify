<?php 

namespace App\Modules\Users\Http\Controllers;

use Cache;
use DB;
use User;
use View;
use Widget;

class RandomUserWidget extends Widget 
{

    public function render(array $parameters = array())
    {
        $minutes = isset($parameters['minutes']) ? (int) $parameters['minutes'] : 10;

        $key = 'users::random_cached';
        $view = Cache::get($key);

        if ($view === null) {
            $user = User::whereBanned(false)->whereNotNull('last_active')->orderBy(DB::raw('RAND()'))->first();

            // If there is no user matching the criteria, show nothing at all
            if (! $user) {
                return '';
            }

            $view = View::make('users::widget_random_user', compact('user'))->render();

            Cache::put($key, $view, $minutes * 60);

            return $view;
        }

        return $view;
    }

}