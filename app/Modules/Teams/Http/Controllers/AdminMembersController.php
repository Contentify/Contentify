<?php

namespace App\Modules\Teams\Http\Controllers;

use App\Modules\Teams\Team;
use BackController;
use DB;
use HTML;
use Request;
use Redirect;
use Response;
use User;
use View;

class AdminMembersController extends BackController
{

    protected $icon = 'users';

    public function __construct()
    {
        $this->modelClass = \User::class;

        parent::__construct();
    }

    public function getIndex()
    {
        $this->indexPage([
            'buttons'   => null,
            'tableHead' => [
                trans('app.id')             => 'id',
                trans('app.username')       => 'username',
                trans('app.object_teams')   => null,
            ],
            'tableRow' => function(User $user)
            {
                $data = [];
                foreach ($user->teams as $team) {
                    $data[$team->id] = e($team->title);
                }

                return [
                    $user->id,
                    HTML::link('users/'.$user->id.'/'.$user->slug, $user->username),
                    raw('<div class="data" data-user-id="'.$user->id.'">'.json_encode($data).'</div>'),
                ];
            },
            'searchFor' => 'username',
            'actions'   => null
        ]);

        $this->pageOutput(HTML::script('vendor/contentify/members.js'));
    }

    /**
     * Delete a team membership
     *
     * @param int $userId The ID of the user
     * @param int $teamId The ID of the team
     * @return string
     */
    public function deleteDelete(int $userId, int $teamId) : string
    {
        $user = User::findOrFail($userId);

        DB::table('team_user')->whereUserId($userId)->whereTeamId($teamId)->delete();

        $data = [];
        foreach ($user->teams as $team) {
            $data[$team->id] = $team->title;
        }

        return json_encode($data);
    }

    /**
     * Return form for joining a team or an empty string if no team is available
     *
     * @param int $userId The ID of the user
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function getAdd(int $userId)
    {
        $teamIds = DB::table('team_user')->whereUserId($userId)->pluck('team_id')->toArray();

        if (sizeof($teamIds) > 0) {
            $teams = Team::whereNotIn('id', $teamIds)->get();
        } else {
            $teams = Team::all();
        }

        if (sizeof($teams) > 0) {
            return View::make('teams::admin_members_team', compact('teams'));
        } else {
            return Response::make('');
        }
    }

    /**
     * Create a team membership
     *
     * @param int $userId The ID of the user
     * @param int $teamId The ID of the team
     * @return string
     */
    public function postAdd(int $userId, int $teamId) : string
    {
        $user = User::findOrFail($userId);

        DB::table('team_user')->insert([
            'team_id' => $teamId,
            'user_id' => $userId,
        ]);

        $data = [];
        foreach ($user->teams as $team) {
            $data[$team->id] = $team->title;
        }

        return json_encode($data);
    }

    /**
     * Return form for team membership details
     *
     * @param int $userId The ID of the user
     * @param int $teamId The ID of the team
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function getEdit(int $userId, int $teamId)
    {
        $user = User::findOrFail($userId);

        foreach ($user->teams as $team) {
            if ($team->id == $teamId) {
                return View::make('teams::admin_members_edit', compact('team'));
            }
        }

        return Response::make(400);
    }

    /**
     * Update team membership details
     *
     * @param int $userId The ID of the user
     * @param int $teamId The ID of the team
     * @return \Illuminate\Http\Response
     */
    public function postUpdate(int $userId, int $teamId)
    {
        $task           = Request::get('task');
        $description    = Request::get('description');
        $position       = (int) Request::get('position');

        DB::table('team_user')->whereUserId($userId)->whereTeamId($teamId)->update([
            'task'          => $task,
            'description'   => $description,
            'position'      => $position,
        ]);

        return Response::make(1);
    }

    /**
     * Helper action method for searching. All we do here is to redirect with the input.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postSearch()
    {
        return Redirect::to('admin/members')->withInput(Request::only('search'));
    }
}
