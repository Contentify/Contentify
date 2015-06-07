<?php namespace App\Modules\Partners\Http\Controllers;

use ModelHandlerTrait;
use App\Modules\Partners\Models\Partnercat;
use Hover, BackController;

class AdminPartnercatsController extends BackController {

    use ModelHandlerTrait;

    protected $icon = 'money';

    public function __construct()
    {
        $this->modelName = 'Partnercat';

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'tableHead' => [
                trans('app.id')     => 'id', 
                trans('app.title')  => 'title'
            ],
            'tableRow' => function($partnercat)
            {
                Hover::modelAttributes($partnercat, ['creator']);

                return [
                    $partnercat->id,
                    raw(Hover::pull(), $partnercat->title),
                ];
            }
        ]);
    }

}