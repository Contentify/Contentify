<?php namespace App\Modules\News\Controllers;

use App\Modules\News\Models\Newscat as Newscat;
use BackController;

class AdminNewscatsController extends BackController {

    protected $icon = 'newspaper.png';

    public function __construct()
    {
        $this->modelName = 'Newscat';

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'tableHead' => [
                trans('app.id') => 'id', 
                trans('app.title') => 'title'
            ],
            'tableRow' => function($newscat)
            {
                return [
                    $newscat->id,
                    $newscat->title
                ];
            }
        ]);
    }

}