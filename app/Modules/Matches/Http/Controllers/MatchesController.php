<?php

namespace App\Modules\Matches\Http\Controllers;

use App\Modules\Matches\Match;
use FrontController;
use HTML;

class MatchesController extends FrontController
{

    public function __construct()
    {
        $this->modelClass = Match::class;

        parent::__construct();
    }

    public function index()
    {
        $this->pageView('matches::filter');

        $this->indexPage([
            'buttons'       => null,
            'brightenFirst' => false,
            'filter'        => true,
            'searchFor' => ['rightTeam', 'title'], 
            'tableHead'     => [
                trans('app.date')               => 'played_at',
                trans('app.object_game')        => 'game_id',
                trans('matches::right_team')    => 'right_team_id',
                trans('matches::score')         => 'left_score'
            ],
            'tableRow'      => function(Match $match)
            {
                if ($match->game->icon) {
                    $game = HTML::image(
                        $match->game->uploadPath().$match->game->icon, 
                        $match->game->title, 
                        ['width' => 16, 'height' => 16]
                    );
                } else {
                    $game = null;
                }

                return [
                    $match->played_at,
                    raw($game),
                    raw(HTML::link(url('matches/'.$match->id), $match->right_team->title)),
                    raw($match->scoreCode())
                ];
            },
            'actions'       => null,
            'pageTitle'     => false,
        ], 'front');
    }

    /**
     * Show a match
     * 
     * @param  int $id The ID of the match
     * @return void
     */
    public function show($id)
    {
        $match = Match::findOrFail($id);

        $match->access_counter++;
        $match->save();

        $this->title($match->leftTeam->title.' '.trans('matches::vs').' '.$match->rightTeam->title);

        $this->pageView('matches::show', compact('match'));
    }
    
}