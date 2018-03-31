<?php 

namespace App\Modules\Partners\Http\Controllers;

use App\Modules\Partners\Partnercat;
use BackController;
use Hover;
use ModelHandlerTrait;

class AdminPartnercatsController extends BackController
{

    use ModelHandlerTrait;

    protected $icon = 'money-bill-alt';

    public function __construct()
    {
        $this->modelClass = Partnercat::class;

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'tableHead' => [
                trans('app.id')     => 'id', 
                trans('app.title')  => 'title'
            ],
            'tableRow' => function(Partnercat $partnercat)
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