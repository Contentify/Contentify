<?php 

namespace App\Modules\Slides\Http\Controllers;

use App\Modules\Slides\Slidecat;
use BackController;
use Hover;
use ModelHandlerTrait;

class AdminSlidecatsController extends BackController
{

    use ModelHandlerTrait;

    protected $icon = 'play';

    public function __construct()
    {
        $this->modelClass = Slidecat::class;

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'tableHead' => [
                trans('app.id')     => 'id', 
                trans('app.title')  => 'title'
            ],
            'tableRow' => function(Slidecat $slidecat)
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