<?php 

namespace App\Modules\Slides\Http\Controllers;

use App\Modules\Slides\SlideCat;
use BackController;
use Hover;
use ModelHandlerTrait;

class AdminSlideCatsController extends BackController
{

    use ModelHandlerTrait;

    protected $icon = 'play';

    public function __construct()
    {
        $this->modelClass = SlideCat::class;

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'tableHead' => [
                trans('app.id')     => 'id', 
                trans('app.title')  => 'title'
            ],
            'tableRow' => function(SlideCat $slideCat)
            {
                Hover::modelAttributes($slideCat, ['creator', 'updated_at']);

                return [
                    $slideCat->id,
                    raw(Hover::pull(), $slideCat->title),
                ];
            }
        ]);
    }

}
