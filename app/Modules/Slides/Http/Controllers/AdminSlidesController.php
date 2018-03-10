<?php 

namespace App\Modules\Slides\Http\Controllers;

use ModelHandlerTrait;
use App\Modules\Slides\Slide;
use HTML, Hover, BackController;

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
                trans('app.published')      => 'published', 
                trans('app.title')          => 'title',
                trans('app.category')       => 'slidecat_id', 
            ],
            'tableRow' => function($slide)
            {
                /** @var Slide $slide */
                Hover::modelAttributes($slide, ['image', 'creator']);

                return [
                    $slide->id,
                    raw($slide->published ? HTML::fontIcon('check') : null),
                    raw(Hover::pull(), $slide->title),
                    $slide->slidecat->title,
                ];            
            }
        ]);
    }

}