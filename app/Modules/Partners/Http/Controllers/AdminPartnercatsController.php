<?php 

namespace App\Modules\Partners\Http\Controllers;

use ModelHandlerTrait;
use App\Modules\Partners\Partnercat;
use Hover, BackController;

class AdminPartnercatsController extends BackController
{

    use ModelHandlerTrait;

    protected $icon = 'money-bill-alt';

    public function __construct()
    {
        $this->modelName = Partnercat::class;

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
                /** @var Partnercat $partnercat */
                Hover::modelAttributes($partnercat, ['creator']);

                return [
                    $partnercat->id,
                    raw(Hover::pull(), $partnercat->title),
                ];
            }
        ]);
    }

}