<?php

namespace App\Modules\Cups\Http\Controllers;

use App\Modules\Cups\Cup;
use App\Modules\Cups\Match;
use BackController;
use Carbon;
use DB;
use Hover;
use HTML;
use ModelHandlerTrait;
use Redirect;

class AdminCupsController extends BackController
{

    use ModelHandlerTrait;

    /**
     * URL to the README.md file on GitHub
     */
    const README_URL = 'https://github.com/Contentify/Contentify/blob/v2.4/app/Modules/Cups/Readme.md';

    protected $icon = 'share-alt';

    protected $formTemplate = 'admin_form';

    public function __construct()
    {
        $this->modelClass = Cup::class;

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'buttons' => [
                button(trans('app.create'), url('admin/cups/create'), 'plus-circle'),
                'config',
                button(trans('app.description'), self::README_URL, 'list')
            ],
            'tableHead' => [
                trans('app.id')            => 'id', 
                trans('app.published')     => 'published', 
                trans('app.closed')        => 'closed', 
                trans('app.title')         => 'title',
                trans('app.object_game')   => 'game_id',
                trans('cups::start_at')    => 'start_at'
            ],
            'tableRow' => function(Cup $cup)
            {
                Hover::modelAttributes($cup, ['image', 'creator', 'updated_at']);

                return [
                    $cup->id,
                    raw($cup->published ? HTML::fontIcon('check') : HTML::fontIcon('times')),
                    raw($cup->closed ? HTML::fontIcon('check') : HTML::fontIcon('times')),
                    raw(Hover::pull().HTML::link('cups/'.$cup->id.'/'.$cup->slug, $cup->title)),
                    $cup->game->short,
                    $cup->start_at
                ];
            },
            'actions'   => [
                function(Cup $cup) {
                    return icon_link(
                        'edit', 
                        trans('app.edit'), 
                        url('admin/cups/edit/'.$cup->id)
                    );
                },
                function(Cup $cup) {
                    return icon_link(
                        'users',
                        trans('app.object_participants'), 
                        url('admin/cups/participants/'.$cup->id)
                    );
                },
                function(Cup $cup) {
                    return icon_link(
                        'share-alt', 
                        trans('cups::seeding'), 
                        url('admin/cups/seed/'.$cup->id)
                    );
                },
                'delete'
            ],
        ]);
    }

    /**
     * Generates the matches for the first round of the cup
     * 
     * @param  int $cupId The ID of the cup
     * @return \Illuminate\Http\RedirectResponse
     */
    public function seed($cupId) 
    {
        /** @var Cup $cup */
        $cup = Cup::findOrFail($cupId);

        // Get all participants that are checked-in and randomize their order
        $participants = $cup->participants()->where(DB::raw('`cups_participants`.`checked_in`'), true)
            ->get()->shuffle();

        if ($participants->isEmpty()) {
            $this->alertFlash(trans('app.not_possible'));
            return Redirect::to('admin/cups');
        }

        // Set the amount of slots to the lowest possible value
        // (not less than the number of participants, of course)
        $slotValues = Cup::$slotValues;
        arsort($slotValues); // Sort from high to low
        foreach ($slotValues as $slots) {
            if ($slots < sizeof($participants)) {
                $cup->save();
                break;
            }
            $cup->slots = $slots;
        }

        $matches = Match::whereCupId($cupId)->where('winner_id', '>', 0)->where('right_participant_id', '!=', 0)->get();

        // (Re-)Seeding is not possible once matches have been played
        if (sizeof($matches) > 0) {
            $this->alertFlash(trans('app.not_possible'));
            return Redirect::to('admin/cups');
        }

        // Delete the existing matches
        Match::whereCupId($cupId)->delete();

        // The number of matches that we will generate is ALWAYS 0.5 * number of slots -
        // it does NOT (directly) depend on the number of wildcard-matches! This fact is very important!
        // This algorithm ensures that wildcard-matches can only appear in the first round of the cup.
        // There will never be wildcard-matches in other rounds than the first one!
        $matches = [];
        for ($matchIndex = 0; $matchIndex < $cup->slots / 2; $matchIndex++) {
            // Note: We know that we have at least 0.5 * slots participants.
            // This is because we have reduced the number of slots to the lowest possible value.
            // (The difference between two slot limits is always 50% of the bigger limit.)
            $leftParticipantId = $participants[$matchIndex]->id;

            // Note: Wildcard-matches will always have set right_participant_id to 0.
            $index = $cup->slots / 2 + $matchIndex;
            $rightParticipantId = isset($participants[$index]) ? $participants[$index]->id : 0;

            $now = new Carbon;
            
            $matches[] = [
                'round'                 => 1,
                'row'                   => $matchIndex + 1,
                'with_teams'            => $cup->forTeams(),
                'left_participant_id'   => $leftParticipantId,
                'right_participant_id'  => $rightParticipantId,
                'winner_id'             => $rightParticipantId > 0 ? 0 : $leftParticipantId,
                'cup_id'                => $cupId,
                'creator_id'            => user()->id,
                'created_at'            => $now,
            ];
        }

        Match::insert($matches);

        $this->alertFlash(trans('app.created', [trans('app.object_matches')]));
        return Redirect::to('admin/cups');
    }

}
