<?php namespace App\Modules\Adverts\Controllers;

use App\Modules\Adverts\Models\Advertcat;
use Hover, BackController;

class AdminAdvertcatsController extends BackController {

    protected $icon = 'money.png';

    public function __construct()
    {
        $this->modelName = 'Advertcat';

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'tableHead' => [
                trans('app.id') => 'id', 
                trans('app.title') => 'title'
            ],
            'tableRow' => function($advertcat)
            {
                Hover::modelAttributes($advertcat, ['creator']);

                return [
                    $advertcat->id,
                    Hover::pull().$advertcat->title
                ];
            }
        ]);
    }

}