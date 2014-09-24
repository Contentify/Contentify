<?php namespace App\Modules\Matches\Controllers;

use App\Modules\Matches\Models\Matchresult;
use BackController;

class AdminMatchresultsController extends BackController {

    protected $icon = 'soccer.png';

    public function __construct()
    {
        $this->modelName = 'Matchresult';

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'tableHead' => [
                trans('app.id')     => 'id', 
                trans('app.title')  => 'title'
            ],
            'tableRow' => function($matchresult)
            {
                return [
                    $matchresult->id,
                    $matchresult->title,
                ];            
            }
        ]);
    }

}