<?php

namespace App\Modules\Cups\Http\Controllers;

use App\Modules\Cups\Cup;
use App\Modules\Cups\Match;
use BackController;
use Carbon;
use DB;
use Hover;
use HTML;
use MsgException;
use ModelHandlerTrait;
use Redirect;

class AdminCupsController extends BackController
{

    use ModelHandlerTrait;

    /**
     * URL to the README.md file on GitHub
     */
    const README_URL = 'https://github.com/Contentify/Contentify/blob/v3.1-beta/app/Modules/Cups/README.md';

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
    public function seed(int $cupId)
    {
        /** @var Cup $cup */
        $cup = Cup::findOrFail($cupId);

        try {
            $cup->seed();
            $this->alertFlash(trans('app.created', [trans('app.object_matches')]));
        } catch (MsgException $exception) {
            $this->alertFlash($exception->getMessage());
        }

        return Redirect::to('admin/cups');
    }
}
