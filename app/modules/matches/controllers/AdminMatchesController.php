<?php namespace App\Modules\Matches\Controllers;

use App\Modules\Matches\Models\Match;
use Hover, BackController;

class AdminMatchesController extends BackController {

    protected $icon = 'soccer.png';

    public function __construct()
    {
        $this->modelName = 'Match';

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'tableHead' => [
                trans('app.id')     => 'id', 
                //trans('app.title')  => 'title'
            ],
            'tableRow' => function($match)
            {
                Hover::modelAttributes($match, ['access_counter', 'creator']);

                return [
                    $match->id,
                    //Hover.pull(),
                ];            
            }
        ]);
    }

}