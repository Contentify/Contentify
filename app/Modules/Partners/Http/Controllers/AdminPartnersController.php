<?php namespace App\Modules\Partners\Http\Controllers;

use ModelHandlerTrait;
use App\Modules\Partners\Models\Partner;
use Hover, BackController;

class AdminPartnersController extends BackController {

    use ModelHandlerTrait;

    protected $icon = 'money';

    public function __construct()
    {
        $this->modelName = 'Partner';

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'buttons'   => ['new', 'category'],
            'tableHead' => [
                trans('app.id')             => 'id', 
                trans('app.title')          => 'title',
                trans('app.category')       => 'partnercat_id', 
            ],
            'tableRow' => function($partner)
            {
                Hover::modelAttributes($partner, ['image', 'access_counter', 'creator']);

                return [
                    $partner->id,
                    raw(Hover::pull(), $partner->title),
                    $partner->partnercat->title,
                ];            
            }
        ]);
    }

}