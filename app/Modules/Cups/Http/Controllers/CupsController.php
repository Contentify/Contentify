<?php

namespace App\Modules\Cups\Http\Controllers;

use App\Modules\Cups\Cup;
use App\Modules\Cups\Team;
use Contentify\GlobalSearchInterface;
use DB;
use FrontController;
use HTML;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Input;
use Redirect;
use URL;
use User;

class CupsController extends FrontController implements GlobalSearchInterface
{

    public function __construct()
    {
        $this->modelClass = Cup::class;

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'buttons'       => null,
            'tableHead'     => [
                trans('app.title')          => 'title', 
                trans('app.slots')          => 'slots',
                trans('app.object_game')    => 'game_id',
                trans('app.date')           => 'starts_at'
            ],
            'tableRow'      => function(Cup $cup)
            {
                return [
                    raw(HTML::link(
                        url('cups/'.$cup->id.'/'.$cup->slug), 
                        $cup->title)
                    ),
                    $cup->slots,
                    $cup->game->short,
                    $cup->start_at
                ];
            },
            'actions'       => null,
            'filter'        => true,
            'permaFilter'   => function(Builder $query)
            {
                return $query->published();
            }
        ], 'front');
    }

    /**
     * Show a cup
     * 
     * @param  int    $id   The ID of the cup
     * @param  string $slug The unique slug
     * @return void
     */
    public function show($id, $slug = null)
    {
        /** @var Cup $cup */
        if ($slug) {
            $cup = Cup::whereSlug($slug)->published()->firstOrFail();
        } else {
            $cup = Cup::whereId($id)->published()->firstOrFail();
        }

        $cup->access_counter++;
        $cup->save();

        $this->title($cup->title);

        $this->pageView('cups::show', compact('cup'));
    }

    /**
     * Show a cup by slug instead of ID
     * 
     * @param  string $slug The unique slug
     * @return void
     */
    public function showBySlug($slug)
    {
        $this->show(null, $slug);
    }

    /**
     * Let a participant join the cup.
     * 
     * @param  int $cupId
     * @param  int $participantId
     * @return RedirectResponse|null
     */
    public function join($cupId, $participantId)
    {
        /** @var Cup $cup */
        $cup = Cup::findOrFail($cupId);

        if ($cup->countParticipants() == $cup->slots) {
            $this->alertFlash(trans('app.cup_full'));
            return Redirect::to('cups/'.$cup->id.'/'.$cup->slug);
        }

        if (! user() or $cup->isUserInCup(user()) 
            or $cup->join_at->timestamp > time() or $cup->check_in_at->timestamp < time()) {
            $this->alertFlash(trans('app.not_possible'));
            return Redirect::to('cups/'.$cup->id.'/'.$cup->slug);
        }

        if ($cup->forTeams()) {
            /** @var Team $team */
            $team = Team::findOrFail($participantId);

            // Only members who are organizers are allowed to do this
            if (! $team->isOrganizer(user())) {
                $this->alertFlash(trans('cups::not_organizer'));
                return Redirect::to('cups/'.$cup->id.'/'.$cup->slug);
            }

            // Get the IDs of all team members
            $memberIds = DB::table('cups_team_members')->whereTeamId($participantId)->pluck('user_id')->toArray();

            // Get the ID of all team members that are already in this cup (with another team)
            /** @var \Illuminate\Database\Eloquent\Collection $cupUserIds */
            $cupUserIds = DB::table('cups_users')->whereCupId($cup->id)
                ->whereIn('user_id', $memberIds)->pluck('user_id');

            if ($cupUserIds->isNotEmpty()) {
                $cupUsers = User::whereIn('id', $cupUserIds->toArray())->get();

                $users = '';
                foreach ($cupUsers as $cupUser) {
                    if ($users) {
                        $users .= ', ';
                    }
                    $users .= '<a href="'.url('users/'.$cupUser->id.'/'.$cupUser->slug).'">'.$cupUser->username.'</a>';
                }
                $this->alertFlash(trans('cups::user_conflict').' '.trans('app.object_users').': '.$users);
                return Redirect::to('cups/'.$cup->id.'/'.$cup->slug);
            }

            $data = [];
            foreach ($memberIds as $memberId) {
                $data[] = ['cup_id' => $cupId, 'user_id' => $memberId];
            }
            DB::table('cups_users')->insert($data);
        } else {
            if ($participantId != user()->id) {
                $this->alertFlash(trans('app.not_possible'));
                return Redirect::to('cups/'.$cup->id.'/'.$cup->slug);
            }
            DB::table('cups_users')->insert(['cup_id' => $cupId, 'user_id' => $participantId]);
        }

        DB::table('cups_participants')->insert(['cup_id' => $cupId, 'participant_id' => $participantId]);

        $this->alertFlash(trans('app.successful'));
        return Redirect::to('cups/'.$cup->id.'/'.$cup->slug);
    }

    /**
     * Tries to check-in the user or the team of the user to the current cup.
     * 
     * @param int   $cupId      The ID of the cup
     * @param bool  $checkOut   If true, check-out instead of check-in
     * @return RedirectResponse|null
     */
    public function checkIn($cupId, $checkOut = false)
    {
        /** @var Cup $cup */
        $cup = Cup::
        findOrFail($cupId);

        if (! user() or $cup->check_in_at->timestamp > time() or $cup->start_at->timestamp < time()) {
            $this->alertError(trans('app.not_possible'));
            return null;
        }

        $participant = $cup->getParticipantOfUser(user());

        if (! $participant) {
            $this->alertError(trans('app.not_possible'));
            return null;
        }

        if ($cup->forTeams() and ! $participant->isOrganizer(user())) {
            $this->alertError(trans('app.not_possible'));
            return null;
        }
        
        $state = $checkOut ? false : true;
        DB::table('cups_participants')->whereCupId($cupId)->whereParticipantId($participant->id)
            ->update(['checked_in' => $state]);

        $this->alertFlash(trans('app.successful'));
        return Redirect::to('cups/'.$cup->id.'/'.$cup->slug);
    }

    /**
     * Tries to check-out the user or the team of the user to the current cup.
     * 
     * @param int $cupId The cup ID
     * @return RedirectResponse
     */
    public function checkOut($cupId)
    {
        return $this->checkIn($cupId, true);
    }

    /**
     * Tries to swap (the position of) two participants during the seeding phase.
     * 
     * @param  int $cupId The cup ID
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function swap($cupId)
    {
        /** @var Cup $cup */
        $cup = Cup::findOrFail($cupId);

        if (! user() or ! user()->isSuperAdmin()) {
            $this->alertError(trans('app.access_denied'));
            return null;
        }

        $firstId = Input::get('first_id');
        $secondId = Input::get('second_id');

        if ($firstId == $secondId) {
            $this->alertError(trans('app.not_possible'));
            return null;
        }

        $firstMatch = null;
        $secondMatch = null;
        $matches = $cup->matches; // They are ordered by round and round-row
        foreach ($matches as $match) {
            if ($match->left_participant_id == $firstId 
                or $match->right_participant_id == $firstId) {
                $firstMatch = $match;
            }
            if ($match->left_participant_id == $secondId 
                or $match->right_participant_id == $secondId) {
                $secondMatch = $match;
            }

            if ($match->round > 1) {
                $this->alertError(trans('app.not_possible'));
                return null;
            }
        }

        if ((! $firstMatch) or (! $secondMatch)) {
            $this->alertError(trans('app.not_possible'));
            return null;
        }

        // Swap IDs
        if ($firstMatch->id == $secondMatch->id) {
            // If a match that is not a wildcard-match has a winner, seeding phase is over!
            if ($firstMatch->winner_id) {
                $this->alertError(trans('app.not_possible'));
                return null;
            }

            $id = $firstMatch->left_participant_id;
            $firstMatch->left_participant_id = $firstMatch->right_participant_id;
            $firstMatch->right_participant_id = $id;

            $firstMatch->forceSave();
        } else {
            if ($firstMatch->left_participant_id == $firstId) {
                $firstMatch->left_participant_id = $secondId;
            } else {
                $firstMatch->right_participant_id = $secondId;
            }

            if ($secondMatch->left_participant_id == $secondId) {
                $secondMatch->left_participant_id = $firstId;
            } else {
                $secondMatch->right_participant_id = $firstId;
            }

            // Update winner_id in wildcard-matches
            if ($firstMatch->right_participant_id == 0) {
                $firstMatch->winner_id = $firstMatch->left_participant_id;
            }
            if ($secondMatch->right_participant_id == 0) {
                $secondMatch->winner_id = $secondMatch->left_participant_id;
            }

            $firstMatch->forceSave();
            $secondMatch->forceSave();
        }

        $this->alertFlash(trans('app.successful'));
        return Redirect::to('cups/'.$cup->id.'/'.$cup->slug);
    }

    /**
     * This method is called by the global search (SearchController->postCreate()).
     * Its purpose is to return an array with results for a specific search query.
     * 
     * @param  string $subject The search term
     * @return string[]
     */
    public function globalSearch($subject)
    {
        $cups = Cup::published()->where('title', 'LIKE', '%'.$subject.'%')->get();

        $results = array();
        foreach ($cups as $cup) {
            $results[$cup->title] = URL::to('cups/'.$cup->id.'/'.$cup->slug);
        }

        return $results;
    }

}
