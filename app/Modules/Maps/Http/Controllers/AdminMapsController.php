<?php namespace App\Modules\Maps\Http\Controllers;

use App\Modules\Maps\Map;
use ModelHandlerTrait;
use Hover, HTML, BackController;

class AdminMapsController extends BackController {

    use ModelHandlerTrait;

    protected $icon = 'globe';

    public function __construct()
    {
        $this->modelName = 'Map';

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'tableHead' => [
                trans('app.id')     => 'id', 
                trans('app.title')  => 'title',
                trans('Game')       => 'game_id'
            ],
            'tableRow' => function($map)
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

                Hover::modelAttributes($map, ['creator']);

                return [
                    $map->id,
                    raw(Hover::pull().$icon, ' '.$map->title),
                    $gameTitle,
                ];            
            }
        ]);
    }

}