<?php

namespace App\Modules\Awards\Http\Controllers;

use App\Modules\Awards\Award;
use ModelHandlerTrait;
use BackController;

class AdminAwardsController extends BackController {

    use ModelHandlerTrait;

    protected $icon = 'trophy';

    public function __construct()
    {
        $this->modelName = 'Award';

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'tableHead' => [
                trans('app.id')         => 'id', 
                trans('app.position')   => 'position', 
                trans('app.title')      => 'title', 
                trans('app.date')       => 'achieved_at'
            ],
            'tableRow' => function($award)
            {
                /** @var Award $award */
                return [
                    $award->id,
                    raw($award->positionIcon()),
                    $award->title,
                    $award->achieved_at
                ];            
            }
        ]);
    }

}