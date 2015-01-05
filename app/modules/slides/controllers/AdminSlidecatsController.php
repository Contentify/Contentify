<?php namespace App\Modules\Slides\Controllers;

use ModelHandlerTrait;
use App\Modules\Slides\Models\Slidecat;
use Hover, BackController;

class AdminSlidecatsController extends BackController {

    use ModelHandlerTrait;

    protected $icon = 'play';

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
                    raw(Hover::pull(), $slidecat->title),
                ];
            }
        ]);
    }

}