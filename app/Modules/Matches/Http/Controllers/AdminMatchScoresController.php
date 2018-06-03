<?php

namespace App\Modules\Matches\Http\Controllers;

use App\Modules\Matches\MatchScore;
use BackController;
use Input;
use Response;
use View;

class AdminMatchScoresController extends BackController
{

    /**
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function store()
    {
        if (! $this->checkAccessCreate()) {
            return Response::make(null, 403);
        }
        
        $matchScore = new MatchScore(Input::all());
        
        $okay = $matchScore->save();

        if (! $okay) {
            return Response::make(null, 400);
        } else {
            return View::make('matches::admin_map', compact('matchScore'));
        }
    }

    /**
     * @param int $id The ID of the match score object
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function update($id)
    {
        if (! $this->checkAccessUpdate()) {
            return Response::make(null, 403);
        }

        $matchScore = MatchScore::findOrFail($id);
        $matchScore->fill(Input::all());

        $okay = $matchScore->save();

        if (! $okay) {
            return Response::make(null, 400);
        } else {
            return View::make('matches::admin_map', compact('matchScore'));
        }
    }

    /**
     * @param int $id The ID of the match score object
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! $this->checkAccessDelete()) {
            return Response::make(null, 403);
        }

        MatchScore::destroy($id);

        return Response::make(null, 200);
    }

}