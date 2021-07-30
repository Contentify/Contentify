<?php

namespace App\Modules\Cups\Http\Controllers;

use App\Modules\Cups\Cup;
use App\Modules\Cups\Team;
use BackController;
use Illuminate\Http\RedirectResponse;
use Request;
use Redirect;
use User;

class AdminParticipantsController extends BackController
{

    protected $icon = 'users';

    /**
     * Show an overview over the participants of the chosen cup
     *
     * @param int $cupId
     * @throws \Exception
     */
    public function index(int $cupId)
    {
        /** @var Cup $cup */
        $cup = Cup::findOrFail($cupId);

        if ($cup->forTeams()) {
            $newParticipants = Team::whereNotIn('id', $cup->participantIds())->get();
        } else {
            $newParticipants = User::whereNotIn('id', $cup->participantIds())->get();
        }

        $this->pageView('cups::admin_participants', compact('cup', 'newParticipants'));
    }

    /**
     * Add a participant to a cup
     *
     * @param int $cupId
     * @return RedirectResponse
     */
    public function add(int $cupId)
    {
        /** @var Cup $cup */
        $cup = Cup::findOrFail($cupId);

        $participantId = (int) Request::get('participant_id');

        $cup->addParticipantById($participantId);

        $this->alertFlash(trans('app.updated', [trans('app.object_cups')]));
        return Redirect::to('admin/cups/participants/'.$cupId);
    }

    /**
     * Delete a participant from a cup
     *
     * @param int $cupId
     * @param int $participantId
     * @return RedirectResponse
     */
    public function delete(int $cupId, int $participantId) : RedirectResponse
    {
        /** @var Cup $cup */
        $cup = Cup::findOrFail($cupId);

        $cup->removeParticipantByid($participantId);

        $this->alertFlash(trans('app.deleted', [trans('app.object_participant')]));
        return Redirect::to('admin/cups/participants/'.$cupId);
    }

    /**
     * The admin page creates an invalid URL ('/admin/participants')
     * so we catch it and redirect to the cups overview page.
     *
     * @return RedirectResponse
     */
    public function redirect() : RedirectResponse
    {
        return Redirect::to('admin/cups');
    }
}
