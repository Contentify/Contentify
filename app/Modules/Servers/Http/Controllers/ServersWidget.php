<?php 

namespace App\Modules\Servers\Http\Controllers;

use App\Modules\Servers\Server;
use View;
use Widget;

class ServersWidget extends Widget
{

    public function render(array $parameters = array())
    {
        $limit = isset($parameters['limit']) ? (int) $parameters['limit'] : self::LIMIT;

        $onlyGameservers = false;
        if (isset($parameters['onlyGameservers'])) {
            $onlyGameservers = (bool) $parameters['onlyGameservers'];
        }

        if ($onlyGameservers) {
            $servers = Server::whereNotNull('game_id')->orderBy('created_at', 'DESC')->published()->take($limit)->get();
        } else {
            $servers = Server::orderBy('created_at', 'DESC')->published()->take($limit)->get();
        }
        
        return View::make('servers::widget', compact('servers'))->render();
    }

}