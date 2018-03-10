<?php

namespace App\Modules\Matches\Http\Controllers;

use App\Modules\Matches\Match;
use App\Modules\Matches\MatchScore;
use View, Widget;

class FeaturedMatchWidget extends Widget {

    public function render($parameters = array())
    {
        $match = Match::orderBy('played_at', 'DESC')->where('state', '!=', Match::STATE_HIDDEN)->first();

        if ($match) {
            return View::make('matches::featured_widget', compact('match'))->render();
        }
    }

}