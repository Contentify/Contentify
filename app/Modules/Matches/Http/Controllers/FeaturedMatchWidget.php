<?php

namespace App\Modules\Matches\Http\Controllers;

use App\Modules\Matches\Match;
use View;
use Widget;

class FeaturedMatchWidget extends Widget
{

    public function render(array $parameters = array())
    {
        $match = Match::orderBy('played_at', 'DESC')->whereFeatured(true)->where('state', '!=', Match::STATE_HIDDEN)->first();

        if ($match) {
            return View::make('matches::featured_widget', compact('match'))->render();
        }
    }

}