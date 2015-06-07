<?php namespace App\Modules\Navigations\Http\Controllers;

use App\Modules\Navigations\Models\Navigation;
use ModelHandlerTrait;
use Hover, HTML, BackController;

class AdminNavigationsController extends BackController {

    use ModelHandlerTrait;

    protected $icon = 'navicon';

    public function __construct()
    {
        $this->modelName = 'Navigation';

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'tableHead' => [
                trans('app.id')     => 'id', 
                trans('app.title')  => 'title'
            ],
            'tableRow' => function($navigation)
            {
                return [
                    $navigation->id,
                    raw(Hover::modelAttributes($navigation, ['creator'])->pull(), $navigation->title),
                ];            
            }
        ]);
    }

}