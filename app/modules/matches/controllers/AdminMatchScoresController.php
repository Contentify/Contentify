<?php namespace App\Modules\Matches\Controllers;

use App\Modules\Matches\Models\Match;
use App\Modules\Matches\Models\MatchScore;
use Response, View, Input, BackController;

class AdminMatchScoresController extends BackController {

    public function store()
    {
        if (! $this->checkAccessCreate()) return Response::make(null, 403);
        
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
        if (! $this->checkAccessUpdate()) return Response::make(null, 403);

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
        if (! $this->checkAccessDelete()) return Response::make(null, 403);

        MatchScore::destroy($id);

        Response::make(null, 200);
    }

}