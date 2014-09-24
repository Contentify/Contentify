<?php namespace App\Modules\Teams\Controllers;

use App\Modules\Teams\Models\Team;
use Hover, BackController;

class AdminTeamsController extends BackController {

    protected $icon = 'group.png';

    public function __construct()
    {
        $this->modelName = 'Team';

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'tableHead' => [
                trans('app.id')         => 'id', 
                trans('app.title')      => 'title',
                trans('app.category')   => 'teamcat_id'
            ],
            'tableRow' => function($team)
            {
                Hover::modelAttributes($team, ['image', 'access_counter', 'creator']);

                return [
                    $team->id,
                    Hover::pull().$team->title,
                    $team->teamcat->title,
                ];            
            }
        ]);
    }

}