<?php 

namespace App\Modules\Servers\Http\Controllers;

use App\Modules\Servers\Server;
use View;
use Widget;

class ServersWidget extends Widget
{

    public function render(array $parameters = [])
    {
        $limit = isset($parameters['limit']) ? (int) $parameters['limit'] : self::LIMIT;

        $onlyGameServers = false;
        if (isset($parameters['onlyGameServers'])) {
            $onlyGameServers = (bool) $parameters['onlyGameServers'];
        }

        if ($onlyGameServers) {
            $servers = Server::whereNotNull('game_id')->orderBy('created_at', 'DESC')->published()->take($limit)->get();
        } else {
            $servers = Server::orderBy('created_at', 'DESC')->published()->take($limit)->get();
        }
        
        return View::make('servers::widget', compact('servers'))->render();
    }

}
