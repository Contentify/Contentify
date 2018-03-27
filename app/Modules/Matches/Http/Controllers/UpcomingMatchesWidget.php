<?php

namespace App\Modules\Matches\Http\Controllers;

use App\Modules\Matches\Match;
use DB;
use View;
use Widget;

class UpcomingMatchesWidget extends Widget
{

    public function render(array $parameters = array())
    {
        $limit = isset($parameters['limit']) ? (int) $parameters['limit'] : self::LIMIT;

        $matches = Match::orderBy('played_at', 'ASC')->where('played_at', '>=', DB::raw('CURRENT_TIMESTAMP'))
            ->take($limit)->get();

        if ($matches) {
            return View::make('matches::widget', compact('matches'))->render();
        }

        return '';
    }

}