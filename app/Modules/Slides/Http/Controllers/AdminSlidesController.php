<?php 

namespace App\Modules\Slides\Http\Controllers;

use App\Modules\Slides\Slide;
use BackController;
use Hover;
use HTML;
use ModelHandlerTrait;

class AdminSlidesController extends BackController
{

    use ModelHandlerTrait;

    protected $icon = 'play';

    public function __construct()
    {
        $this->modelClass = Slide::class;

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
                trans('app.category')       => 'slide_cat_id',
            ],
            'tableRow' => function(Slide $slide)
            {
                Hover::modelAttributes($slide, ['image', 'creator']);

                return [
                    $slide->id,
                    raw($slide->published ? HTML::fontIcon('check', 'updated_at') : HTML::fontIcon('times')),
                    raw(Hover::pull(), $slide->title),
                    $slide->slideCat->title,
                ];
            }
        ]);
    }

}
