<?php namespace App\Modules\Cups\Http\Controllers;

use ModelHandlerTrait;
use App\Modules\Cups\Cup;
use App\Modules\Cups\Team;
use Redirect, DB, Input, User, HTML, Hover, BackController;

class AdminParticipantsController extends BackController {

    protected $icon = 'group';

    public function index($cupId)
    {
        $cup = Cup::findOrFail($cupId);

        if ($cup->forTeams()) {
            $newParticipants = Team::whereNotIn('id', $cup->participantIds())->get();
        } else {
            $newParticipants = User::whereNotIn('id', $cup->participantIds())->get();
        }

        $this->pageView('cups::admin_participants', compact('cup', 'newParticipants'));
    }

    public function add($cupId)
    {
        $cup = Cup::findOrFail($cupId);

        $participantId = (int) Input::get('participant_id');

        DB::table('cups_participants')->insert([
            'cup_id' => $cup->id,
            'participant_id' => $participantId,
        ]);

        $this->alertFlash(trans('app.updated', [trans('app.object_cups')]));
        return Redirect::to('admin/cups/participants/'.$cupId);
    }

    public function delete($cupId, $participantId)
    {
        $cup = Cup::findOrFail($cupId);

        DB::table('cups_participants')->whereCupId($cup->id)->whereParticipantId($participantId)->delete();

        $this->alertFlash(trans('app.deleted', [trans('app.object_participant')]));
        return Redirect::to('admin/cups/participants/'.$cupId);
    }

    /**
     * The admin page creates an invalid URL ('/admin/paritcipants') 
     * so we catch it an redirect to the cups overview page.
     * 
     * @return Response
     */
    public function redirect()
    {
        return Redirect::to('admin/cups');
    }

}