<?php 

namespace App\Modules\Teams\Http\Controllers;

use App\Modules\Teams\Team;
use View;
use Widget;

class TeamsWidget extends Widget 
{

    public function render(array $parameters = array())
    {
        if (isset($parameters['categoryId'])) {
            $teams = Team::whereTeamcatId($parameters['categoryId'])->published()->orderBy('title', 'ASC')->get();
        } else {
            $teams = Team::published()->orderBy('title', 'ASC')->get();
        }

        return View::make('teams::widget', compact('teams'))->render();
    }

}