<?php 

namespace App\Modules\Partners\Http\Controllers;

use App\Modules\Partners\PartnerCat;
use BackController;
use Hover;
use ModelHandlerTrait;

class AdminPartnerCatsController extends BackController
{

    use ModelHandlerTrait;

    protected $icon = 'money-bill-alt';

    public function __construct()
    {
        $this->modelClass = PartnerCat::class;

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'tableHead' => [
                trans('app.id')     => 'id', 
                trans('app.title')  => 'title'
            ],
            'tableRow' => function(PartnerCat $partnerCat)
            {
                Hover::modelAttributes($partnerCat, ['creator', 'updated_at']);

                return [
                    $partnerCat->id,
                    raw(Hover::pull(), $partnerCat->title),
                ];
            }
        ]);
    }

}
