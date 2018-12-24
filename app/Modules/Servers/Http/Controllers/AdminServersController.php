<?php 

namespace App\Modules\Servers\Http\Controllers;

use App\Modules\Servers\Server;
use BackController;
use Hover;
use HTML;
use ModelHandlerTrait;

class AdminServersController extends BackController
{

    use ModelHandlerTrait;

    protected $icon = 'database';

    public function __construct()
    {
        $this->modelClass = Server::class;

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'tableHead' => [
                trans('app.id')             => 'id', 
                trans('app.published')      => 'published', 
                trans('app.object_game')    => 'game_id', 
                trans('app.title')          => 'title',
                trans('app.ip')             => 'ip',
            ],
            'tableRow' => function(Server $server)
            {
                $gameIcon = ($server->game and $server->game->icon) ?
                    HTML::image($server->game->uploadPath().$server->game->icon, $server->game->title) :
                    null;

                return [
                    $server->id,
                    raw($server->published ? HTML::fontIcon('check') : HTML::fontIcon('times')),
                    raw($gameIcon),
                    raw(Hover::modelAttributes($server, ['creator', 'updated_at'])->pull().HTML::link('servers', $server->title)),
                    $server->ip,
                ];
            }
        ]);
    }

}
