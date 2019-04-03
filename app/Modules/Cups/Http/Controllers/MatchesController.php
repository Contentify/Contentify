<?php

namespace App\Modules\Cups\Http\Controllers;

use App\Modules\Cups\Match;
use FrontController;
use Illuminate\Http\RedirectResponse;
use Input;
use Redirect;

class MatchesController extends FrontController
{

    public function __construct()
    {
        $this->modelClass = Match::class;

        parent::__construct();
    }

    /**
     * Shows a cup match
     *
     * @param  int $id The ID of the match
     * @return void
     * @throws \Exception
     */
    public function show(int $id)
    {
        /** @var Match $match */
        $match = Match::findOrFail($id);

        if ($match->with_teams) {
            $leftName = $match->left_participant ? $match->left_participant->title : 'Wildcard';
            $rightName = $match->right_participant ? $match->right_participant->title : 'Wildcard';
        } else {
            $leftName = $match->left_participant ? $match->left_participant->username : 'Wildcard';
            $rightName = $match->right_participant ? $match->right_participant->username : 'Wildcard';
        }
        $this->title($leftName.' '.trans('matches::vs').' '.$rightName); 

        $canConfirmLeft = $match->canConfirmLeft(user());
        $canConfirmRight = $match->canConfirmRight(user());

        $this->pageView('cups::show_match', compact('match', 'canConfirmLeft', 'canConfirmRight'));
    }

    /**
     * Confirms the result (score) of a participant.
     * 
     * @param  int  $id   The ID of the match
     * @param  bool $left If true, confirm the left result. If false, confirm the right.
     * @return RedirectResponse
     */
    public function confirm(int $id, bool $left = true)
    {
        /** @var Match $match */
        $match = Match::findOrFail($id);

        try {
            $newMatch = $match->confirm(Input::get('left_score'), Input::get('right_score'));
        } catch (MsgException $exception) {
            $this->alertFlash($exception->getMessage());
            return Redirect::to('cups/matches/'.$match->id);
        }

        if ($newMatch) {
            $this->alertFlash(trans('cups::new_match'));
            return Redirect::to('cups/matches/'.$newMatch->id);
        } else {
            $this->alertFlash(trans('app.successful'));
            return Redirect::to('cups/matches/'.$match->id);
        }
    }

    /**
     * Confirms the result (score) of the left participant.
     * 
     * @param  int  $id The ID of the match
     * @return RedirectResponse
     */
    public function confirmLeft(int $id)
    {
        return $this->confirm($id, true);
    }

    /**
     * Confirms the result (score) of the right participant.
     * 
     * @param  int  $id The ID of the match
     * @return RedirectResponse
     */
    public function confirmRight(int $id)
    {
        return $this->confirm($id, false);
    }

    /**
     * Tries to update the winner of a match (not of a wildcard-match!)
     * 
     * @return RedirectResponse|null
     */
    public function winner()
    {
        /** @var Match $match */
        $match = Match::findOrFail(Input::get('match_id'));

        if (! user() or ! user()->isSuperAdmin()) {
            $this->alertError(trans('app.access_denied'));
            return null;
        }

        try {
            $match->updateWinner();
        } catch (MsgException $exception) {
            $this->alertError($exception->getMessage());
            return null;
        }
        
        $this->alertFlash(trans('app.successful'));
        return Redirect::to('cups/matches/'.$match->id);
    }
}
