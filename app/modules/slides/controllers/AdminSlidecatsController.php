<?php namespace App\Modules\Slides\Controllers;

use App\Modules\Slides\Models\Slidecat;
use Hover, BackController;

class AdminSlidecatsController extends BackController {

    protected $icon = 'color_swatch.png';

    public function __construct()
    {
        $this->modelName = 'Slidecat';

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'tableHead' => [
                trans('app.id')     => 'id', 
                trans('app.title')  => 'title'
            ],
            'tableRow' => function($slidecat)
            {
                Hover::modelAttributes($slidecat, ['creator']);

                return [
                    $slidecat->id,
                    Hover::pull().$slidecat->title
                ];
            }
        ]);
    }

}