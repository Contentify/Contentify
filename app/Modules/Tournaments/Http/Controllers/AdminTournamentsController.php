<?php 

namespace App\Modules\Tournaments\Http\Controllers;

use App\Modules\Tournaments\Tournament;
use BackController;
use Hover;
use ModelHandlerTrait;

class AdminTournamentsController extends BackController 
{

    use ModelHandlerTrait;

    protected $icon = 'crosshairs';

    public function __construct()
    {
        $this->modelClass = Tournament::class;

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'tableHead' => [
                trans('app.id')     => 'id', 
                trans('app.title')  => 'title'
            ],
            'tableRow' => function(Tournament $tournament)
            {
                Hover::modelAttributes($tournament, ['icon', 'creator', 'updated_at']);

                return [
                    $tournament->id,
                    raw(Hover::pull(), $tournament->title),
                ];
            }
        ]);
    }

}
