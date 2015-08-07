<?php namespace App\Modules\Teams\Http\Controllers;

use App\Modules\Teams\Team;
use View, Widget;

class TeamsWidget extends Widget {

    public function render($parameters = array())
    {
    	$limit = isset($parameters['limit']) ? (int) $parameters['limit'] : self::LIMIT;

        if (isset($parameters['categoryId'])) {
            $teams = Teams::whereTeamcatId($parameters['categoryId'])->orderBy('title', 'ASC')->get();
        } else {
            $teams = Teams::orderBy('title', 'ASC')->get();
        }

        return View::make('teams::widget', compact('teams'))->render();
    }

}