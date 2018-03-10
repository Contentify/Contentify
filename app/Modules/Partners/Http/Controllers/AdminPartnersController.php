<?php 

namespace App\Modules\Partners\Http\Controllers;

use ModelHandlerTrait;
use App\Modules\Partners\Partner;
use HTML, Hover, BackController;

class AdminPartnersController extends BackController {

    use ModelHandlerTrait;

    protected $icon = 'money-bill-alt';

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
                trans('app.published')      => 'published', 
                trans('app.title')          => 'title',
                trans('app.category')       => 'partnercat_id', 
            ],
            'tableRow' => function($partner)
            {
                /** @var Partner $partner */
                Hover::modelAttributes($partner, ['image', 'access_counter', 'creator']);

                return [
                    $partner->id,
                    raw($partner->published ? HTML::fontIcon('check') : null),
                    raw(Hover::pull(), $partner->title),
                    $partner->partnercat->title,
                ];            
            }
        ]);
    }

}