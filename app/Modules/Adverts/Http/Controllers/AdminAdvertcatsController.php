<?php

namespace App\Modules\Adverts\Http\Controllers;

use App\Modules\Adverts\Advertcat;
use BackController;
use Hover;
use ModelHandlerTrait;

class AdminAdvertcatsController extends BackController
{

    use ModelHandlerTrait;

    protected $icon = 'bullhorn';

    public function __construct()
    {
        $this->modelClass = Advertcat::class;

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'tableHead' => [
                trans('app.id') => 'id', 
                trans('app.title') => 'title'
            ],
            'tableRow' => function(Advertcat $advertcat)
            {
                Hover::modelAttributes($advertcat, ['creator']);

                return [
                    $advertcat->id,
                    raw(Hover::pull(), $advertcat->title),
                ];
            }
        ]);
    }

}