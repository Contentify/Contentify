<?php

namespace App\Modules\Awards\Http\Controllers;

use App\Modules\Awards\Award;
use View;
use Widget;

class AwardsWidget extends Widget
{

    public function render(array $parameters = array())
    {
        $teamId = isset($parameters['teamId']) ? (int) $parameters['teamId'] : null;
        $limit = isset($parameters['limit']) ? (int) $parameters['limit'] : self::LIMIT;

        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = Award::orderBy('achieved_at', 'DESC');
        if ($teamId) {
            $query->where('team_id', '=', $teamId);
        }
        $awards = $query->take($limit)->get();

        return View::make('awards::widget', compact('awards'))->render();
    }

}