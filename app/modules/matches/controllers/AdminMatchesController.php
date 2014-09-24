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
                //$hover = new Hover();
                //if ($match->icon) $hover->image($game->uploadPath().$game->icon);

                return [
                    $match->id,
                    //$game->title,
                ];            
            }
        ]);
    }

}