<?php

namespace App\Modules\Adverts\Http\Controllers;

use App\Modules\Adverts\AdvertCat;
use BackController;
use Hover;
use ModelHandlerTrait;

class AdminAdvertCatsController extends BackController
{

    use ModelHandlerTrait;

    protected $icon = 'bullhorn';

    public function __construct()
    {
        $this->modelClass = AdvertCat::class;

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'tableHead' => [
                trans('app.id') => 'id', 
                trans('app.title') => 'title'
            ],
            'tableRow' => function(AdvertCat $advertCat)
            {
                Hover::modelAttributes($advertCat, ['creator', 'updated_at']);

                return [
                    $advertCat->id,
                    raw(Hover::pull(), $advertCat->title),
                ];
            }
        ]);
    }

}
