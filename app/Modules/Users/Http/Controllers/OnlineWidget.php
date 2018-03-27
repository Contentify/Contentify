<?php 

namespace App\Modules\Users\Http\Controllers;

use User;
use View;
use Widget;

class OnlineWidget extends Widget 
{

    public function render(array $parameters = array())
    {
        $limit = isset($parameters['limit']) ? (int) $parameters['limit'] : self::LIMIT;

        $users = User::online()->orderBy('last_active', 'DESC')->take($limit)->get();

        return View::make('users::widget_online', compact('users'))->render();
    }

}