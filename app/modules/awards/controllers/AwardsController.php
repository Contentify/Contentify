<?php namespace App\Modules\Awards\Controllers;

use App\Modules\Awards\Models\Award;
use HTML, URL, FrontController;

class AwardsController extends FrontController {

    public function __construct()
    {
        $this->modelName = 'Award';

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'buttons'   => null,
            'tableHead' => [
                trans('app.position')   => 'position', 
                trans('app.title')      => 'title', 
                trans('Tournament')     => 'tournament_id',
                trans('Game')           => 'game_id',
                trans('app.date')       => 'achieved_at',
            ],
            'tableRow'  => function($award)
            {
                $game = '';
                if ($award->game->icon) {
                    $game = HTML::image(
                        $award->game->uploadPath().$award->game->icon, 
                        $award->game->title, // "alt" attribute
                        ['title' => $award->game->title]); // "title" attribute
                }

                return [
                    $award->positionIcon(),
                    $award->url ? HTML::link($award->url, $award->title) : $award->title,
                    $award->tournament ? $award->tournament->short : null,
                    $game,
                    $award->achieved_at,
                ];
            },
            'actions'   => null,
            'filter'    => true
        ], 'front');
    }

}