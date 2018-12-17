<?php

namespace App\Modules\Maps\Http\Controllers;

use App\Modules\Maps\Map;
use BackController;
use Hover;
use HTML;
use ModelHandlerTrait;

class AdminMapsController extends BackController
{

    use ModelHandlerTrait;

    protected $icon = 'globe';

    public function __construct()
    {
        $this->modelClass = Map::class;

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'tableHead' => [
                trans('app.id')             => 'id', 
                trans('app.title')          => 'title',
                trans('app.object_game')    => 'game_id'
            ],
            'tableRow' => function(Map $map)
            {
                $icon = null;
                if ($map->image) {
                    Hover::image($map->uploadPath().$map->image);
                    $icon = HTML::image($map->uploadPath().'16/'.$map->image, $map->title) ;
                }

                $gameTitle = null;
                if ($map->game) {
                    $gameTitle = $map->game->title;
                }

                Hover::modelAttributes($map, ['creator', 'updated_at']);

                return [
                    $map->id,
                    raw(Hover::pull().$icon, ' '.$map->title),
                    $gameTitle,
                ];
            }
        ]);
    }

}
