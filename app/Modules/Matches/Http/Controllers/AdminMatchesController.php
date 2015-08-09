<?php namespace App\Modules\Matches\Http\Controllers;

use ModelHandlerTrait;
use App\Modules\Matches\Match;
use App\Modules\Matches\MatchScore;
use App\Modules\Maps\Map;
use Input, HTML, Hover, BackController;

class AdminMatchesController extends BackController {

    use ModelHandlerTrait {
        create as traitCreate;
    }

    protected $icon = 'crosshairs';

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
                    raw($match->state == 1 ? HTML::fontIcon('check') : null),
                    raw(Hover::pull().HTML::link('matches/'.$match->id, $match->left_team->title)),
                    raw(HTML::link('matches/'.$match->id, $match->right_team->title)),
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