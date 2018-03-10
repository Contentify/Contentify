<?php 

namespace App\Modules\Tournaments\Http\Controllers;

use ModelHandlerTrait;
use App\Modules\Tournaments\Tournament;
use Hover, BackController;

class AdminTournamentsController extends BackController {

    use ModelHandlerTrait;

    protected $icon = 'crosshairs';

    public function __construct()
    {
        $this->modelName = 'Tournament';

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'tableHead' => [
                trans('app.id')     => 'id', 
                trans('app.title')  => 'title'
            ],
            'tableRow' => function($tournament)
            {
                /** @var Tournament $tournament */
                Hover::modelAttributes($tournament, ['icon', 'creator']);

                return [
                    $tournament->id,
                    raw(Hover::pull(), $tournament->title),
                ];            
            }
        ]);
    }

}