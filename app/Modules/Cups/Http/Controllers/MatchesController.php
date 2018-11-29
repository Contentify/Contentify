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
     * @param  int  $id The ID of the match
     * @return void
     */
    public function show($id)
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
    public function confirm($id, $left = true)
    {
        /** @var Match $match */
        $match = Match::findOrFail($id);

        $leftScore = (int) Input::get('left_score');
        $rightScore = (int) Input::get('right_score');

        if ($leftScore == $rightScore) {
            $this->alertFlash(trans('app.not_possible'));
            return Redirect::to('cups/matches/'.$match->id);
        }

        if ($left) {
            if (! $match->canConfirmLeft(user())) {
                $this->alertFlash(trans('app.access_denied'));
                return Redirect::to('cups/matches/'.$match->id);
            }

            // If the result has been changed by the left participant, the right has to confirm it again
            if ($match->left_score != $leftScore or $match->right_score != $rightScore) {
                $match->right_confirmed = false;
            }

            $match->left_confirmed = true; 
        } else {
            if (! $match->canConfirmRight(user())) {
                $this->alertFlash(trans('app.access_denied'));
                return Redirect::to('cups/matches/'.$match->id);
            }
 
            // If the result has been changed by the right participant, the left has to confirm it again
            if ($match->left_score != $leftScore or $match->right_score != $rightScore) {
                $match->left_confirmed = false;
            }

            $match->right_confirmed = true; 
        }
        
        $match->left_score = $leftScore;
        $match->right_score = $rightScore;
        $match->save();

        $newMatch = $match->generateNext();

        // Create next matches for wildcard-matches
        if ($match->round == 1) {
            // Remember: Wildcard-matches can only appear in the first row (so we do not need to check this)
            $wildcards = Match::whereCupId($match->cup_id)->whereRightParticipantId(0)->whereNextMatchId(0)
                ->orderBy('row')->get();

            /** @var Match $wildcard */
            foreach ($wildcards as $wildcard) {
                // It's enough to create  the next match of one of the pair matches
                if ($wildcard->row % 2 == 1) { 
                    $wildcard->generateNext();
                }
            }
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
    public function confirmLeft($id)
    {
        return $this->confirm($id, true);
    }

    /**
     * Confirms the result (score) of the right participant.
     * 
     * @param  int  $id The ID of the match
     * @return RedirectResponse
     */
    public function confirmRight($id)
    {
        return $this->confirm($id, false);
    }

    /**
     * Tries to change the winner of a match (not of a wildcard-match!)
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

        $nextMatch = $match->nextMatch();

        if (! $match->right_participant_id or ! $match->winner_id or ! $nextMatch or $nextMatch->winner_id) {
            $this->alertError(trans('app.not_possible'));
            return null;
        }

        if ($match->left_participant_id == $match->winner_id) {
            $match->winner_id = $match->right_participant_id;
            $match->left_score = 0;
            $match->right_score = 1;
        } else {
            $match->winner_id = $match->left_participant_id;
            $match->left_score = 1;
            $match->right_score = 0;
        }

        if ($match->row == 2 * $nextMatch->row) {
            $nextMatch->right_participant_id = $match->winner_id;
        } else {
            $nextMatch->left_participant_id = $match->winner_id;
        }

        $match->forceSave();
        $nextMatch->forceSave();

        $this->alertFlash(trans('app.successful'));
        return Redirect::to('cups/matches/'.$match->id);
    }

}
