<?php

namespace App\Modules\Navigations\Http\Controllers;

use App\Modules\Navigations\Navigation;
use BackController;
use Hover;
use ModelHandlerTrait;

class AdminNavigationsController extends BackController
{

    use ModelHandlerTrait;

    protected $icon = 'bars';

    public function __construct()
    {
        $this->modelClass = Navigation::class;

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'tableHead' => [
                trans('app.id')     => 'id', 
                trans('app.title')  => 'title'
            ],
            'tableRow' => function(Navigation $navigation)
            {
                return [
                    $navigation->id,
                    raw(Hover::modelAttributes($navigation, ['creator', 'updated_at'])->pull(), $navigation->title),
                ];
            }
        ]);
    }

}
