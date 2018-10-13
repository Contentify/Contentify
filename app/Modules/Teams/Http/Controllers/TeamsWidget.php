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
            $teams = Team::whereTeamCatId($parameters['categoryId'])->published()->orderBy('position', 'ASC')->get();
        } else {
            $teams = Team::published()->orderBy('position', 'ASC')->get();
        }

        return View::make('teams::widget', compact('teams'))->render();
    }

}
