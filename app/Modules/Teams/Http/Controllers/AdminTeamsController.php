<?php 

namespace App\Modules\Teams\Http\Controllers;

use App\Modules\Teams\Team;
use BackController;
use Hover;
use HTML;
use ModelHandlerTrait;

class AdminTeamsController extends BackController 
{

    use ModelHandlerTrait;

    protected $icon = 'flag';

    public function __construct()
    {
        $this->modelClass = Team::class;

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'buttons'   => ['new', HTML::button(trans('app.object_members'), url('admin/members'), 'users')],
            'tableHead' => [
                trans('app.id')         => 'id',
                trans('app.published')  => 'published', 
                trans('app.title')      => 'title',
                trans('app.category')   => 'team_cat_id'
            ],
            'tableRow' => function(Team $team)
            {
                Hover::modelAttributes($team, ['image', 'access_counter', 'creator', 'updated_at']);

                return [
                    $team->id,
                    raw($team->published ? HTML::fontIcon('check') : HTML::fontIcon('times')),
                    raw(Hover::pull().HTML::link('teams/'.$team->id.'/'.$team->slug, $team->title)),
                    $team->teamCat->title,
                ];
            }
        ]);
    }

    /**
     * Returns the lineup of a team
     * 
     * @param  int $id The ID of the team
     * @return string
     */
    public function lineup($id)
    {
        /** @var Team $team */
        $team = Team::findOrFail($id);

        $lineup = '';
        foreach ($team->members as $user) {
            if ($lineup) {
                $lineup .= ', ';
            }
            $lineup .= $user->username;
        }

        return $lineup;
    }

}
