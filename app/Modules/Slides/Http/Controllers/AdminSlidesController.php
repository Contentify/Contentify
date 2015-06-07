<?php namespace App\Modules\Slides\Http\Controllers;

use ModelHandlerTrait;
use App\Modules\Slides\Models\Slide;
use Hover, BackController;

class AdminSlidesController extends BackController {

    use ModelHandlerTrait;

    protected $icon = 'play';

    public function __construct()
    {
        $this->modelName = 'Slide';

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'buttons'   => ['new', 'category'],
            'tableHead' => [
                trans('app.id')             => 'id', 
                trans('app.title')          => 'title',
                trans('app.category')       => 'slidecat_id', 
            ],
            'tableRow' => function($slide)
            {
                Hover::modelAttributes($slide, ['image', 'creator']);

                return [
                    $slide->id,
                    raw(Hover::pull(), $slide->title),
                    $slide->slidecat->title,
                ];            
            }
        ]);
    }

}