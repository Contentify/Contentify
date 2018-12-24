<?php 

namespace App\Modules\Partners\Http\Controllers;

use App\Modules\Partners\Partner;
use BackController;
use Hover;
use HTML;
use ModelHandlerTrait;

class AdminPartnersController extends BackController
{

    use ModelHandlerTrait;

    protected $icon = 'money-bill-alt';

    public function __construct()
    {
        $this->modelClass = Partner::class;

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
                trans('app.category')       => 'partner_cat_id',
            ],
            'tableRow' => function(Partner $partner)
            {
                Hover::modelAttributes($partner, ['image', 'access_counter', 'creator', 'updated_at']);

                return [
                    $partner->id,
                    raw($partner->published ? HTML::fontIcon('check') : HTML::fontIcon('times')),
                    raw(Hover::pull().HTML::link('partners#partner-section-'.$partner->slug, $partner->title)),
                    $partner->partnerCat->title,
                ];
            }
        ]);
    }

}
