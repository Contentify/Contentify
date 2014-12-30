<?php namespace App\Modules\Matches\Controllers;

use ModelHandlerTrait;
use App\Modules\Matches\Models\Match;
use App\Modules\Matches\Models\MatchScore;
use App\Modules\Maps\Models\Map;
use Input, HTML, Hover, BackController;

class AdminMatchesController extends BackController {

    use ModelHandlerTrait {
        create as traitCreate;
    }

    protected $icon = 'sport_soccer.png';

    public function __construct()
    {
        $this->modelName = 'Match';

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'searchFor' => ['leftTeam', 'title'], 
            'tableHead' => [
                trans('app.id')                 => 'id',
                trans('app.state')              => 'state',
                trans('matches::left_team')     => 'left_team_id',
                trans('matches::right_team')    => 'right_team_id',
                trans('Tournament')             => 'tournament_id',
                trans('matches::played_at')     => 'played_at'
            ],
            'tableRow' => function($match)
            {
                Hover::modelAttributes($match, ['access_counter', 'creator']);

                return [
                    $match->id,
                    raw($match->state == 1 ? HTML::image(asset('icons/accept.png'), trans('app.yes')) : null),
                    raw(Hover::pull(), $match->left_team->title),
                    $match->right_team->title,
                    $match->tournament->short,
                    $match->played_at
                ];            
            }
        ]);
    }

    public function create()
    {
        $this->traitCreate();

        $maps = Map::all();

        $this->layout->page->with('maps');
    }

}