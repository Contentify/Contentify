<?php namespace App\Modules\Teams\Http\Controllers;

use ModelHandlerTrait;
use App\Modules\Teams\Team;
use Hover, HTML, BackController;

class AdminTeamsController extends BackController {

    use ModelHandlerTrait;

    protected $icon = 'flag';

    public function __construct()
    {
        $this->modelName = 'Team';

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'tableHead' => [
                trans('app.id')         => 'id', 
                trans('app.title')      => 'title',
                trans('app.category')   => 'teamcat_id'
            ],
            'tableRow' => function($team)
            {
                Hover::modelAttributes($team, ['image', 'access_counter', 'creator']);

                return [
                    $team->id,
                    raw(Hover::pull().HTML::link('teams/'.$team->id.'/'.$team->slug, $team->title)),
                    $team->teamcat->title,
                ];            
            }
        ]);
    }

    /**
     * Returns the lineup of a team
     * 
     * @param  int      $id ID of the opponent
     * @return string
     */
    public function lineup($id)
    {
        $team = Team::findOrFail($id);

        $lineup = '';
        foreach ($team->members as $user) {
            if ($lineup) $lineup .= ', ';
            $lineup .= $user->username;
        }

        return $lineup;
    }

}