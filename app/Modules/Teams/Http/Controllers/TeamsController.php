<?php 

namespace App\Modules\Teams\Http\Controllers;

use App\Modules\Teams\Team;
use FrontController;

class TeamsController extends FrontController 
{

    public function __construct()
    {
        $this->modelClass = Team::class;

        parent::__construct();
    }

    public function index()
    {
        $teams = Team::published()->orderBy('position', 'ASC')->get();

        $this->pageView('teams::index', compact('teams'));
    }

    /**
     * Show a team
     * 
     * @param  int $id The id of the team
     * @return void
     */
    public function show($id)
    {
        $team = Team::published()->findOrFail($id);

        $team->access_counter++;
        $team->save();

        $this->title($team->title);

        $this->pageView('teams::show', compact('team'));
    }

}