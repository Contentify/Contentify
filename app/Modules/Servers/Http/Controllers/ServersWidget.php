<?php namespace App\Modules\Servers\Http\Controllers;

use App\Modules\Servers\Server;
use View, Widget;

class ServersWidget extends Widget {

    public function render($parameters = array())
    {
        $onlyGameservers = false;
        if (isset($parameters['onlyGameservers'])) {
            $onlyGameservers = (bool) $parameters['onlyGameservers'];
        }

        if ($onlyGameservers) {
            $servers = Server::whereNotNull('game_id')->orderBy('created_at', 'DESC')->published()->take(5)->get();
        } else {
            $servers = Server::orderBy('created_at', 'DESC')->published()->take(5)->get();
        }
        
        return View::make('servers::widget', compact('servers'))->render();
    }

}