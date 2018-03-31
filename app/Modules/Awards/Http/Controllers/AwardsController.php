<?php

namespace App\Modules\Awards\Http\Controllers;

use App\Modules\Awards\Award;
use FrontController;
use HTML;

class AwardsController extends FrontController
{

    public function __construct()
    {
        $this->modelClass = Award::class;

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'buttons'       => null,
            'brightenFirst' => false,
            'tableHead'     => [
                trans('app.position')           => 'position', 
                trans('app.title')              => 'title', 
                trans('app.object_tournament')  => 'tournament_id',
                trans('app.object_game')        => 'game_id',
                trans('app.date')               => 'achieved_at',
            ],
            'tableRow'      => function(Award $award)
            {
                $game = '';
                if ($award->game->icon) {
                    $game = HTML::image(
                        $award->game->uploadPath().$award->game->icon, 
                        e($award->game->title), // "alt" attribute
                        ['title' => e($award->game->title)]); // "title" attribute
                }

                return [
                    raw($award->positionIcon()),
                    raw($award->url ? HTML::link($award->url, e($award->title)) : e($award->title)),
                    $award->tournament ? $award->tournament->short : null,
                    raw($game),
                    $award->achieved_at,
                ];
            },
            'actions'       => null,
        ], 'front');
    }

}