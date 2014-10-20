<?php namespace App\Modules\Matches\Controllers;

use App\Modules\Matches\Models\Match;
use App\Modules\Matches\Models\MatchScore;
use Response, View, Input, BackController;

class AdminMatchScoresController extends BackController {

    public function store()
    {
        $matchScore = new MatchScore(Input::all());
        
        $okay = $matchScore->save();

        if (! $okay) {
            return Response::make(null, 500);
        } else {
            return View::make('matches::admin_map', compact('matchScore'));
        }
    }

    public function update($id)
    {
        $matchScore = MatchScore::findOrFail($id);
        $matchScore->fill(Input::all());

        $okay = $matchScore->save();

        if (! $okay) {
            return Response::make(null, 500);
        } else {
            return View::make('matches::admin_map', compact('matchScore'));
        }
    }

    public function destroy($id)
    {
        MatchScore::destroy($id);

        Response::make(null, 200);
    }

}