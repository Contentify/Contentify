<?php namespace App\Modules\Opponents\Controllers;

use App\Modules\Opponents\Models\Opponent;
use HTML, Hover, BackController;

class AdminOpponentsController extends BackController {

    protected $icon = 'controller.png';

    public function __construct()
    {
        $this->modelName = 'Opponent';

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'tableHead' => [
                trans('app.id')     => 'id', 
                trans('app.title')  => 'title'
            ],
            'tableRow' => function($opponent)
            {
                Hover::modelAttributes($opponent, ['image', 'creator']);

                $icon = $opponent->country->icon 
                    ? HTML::image($opponent->country->uploadPath().$opponent->country->icon, $opponent->country->title)
                    : null;

                return [
                    $opponent->id,
                    Hover::pull().$icon.' '.$opponent->title,
                ];            
            }
        ]);
    }

}