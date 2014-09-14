<?php namespace App\Modules\Partners\Controllers;

use App\Modules\Partners\Models\Partner;
use Hover, BackController;

class AdminPartnersController extends BackController {

    protected $icon = 'coins.png';

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
                $hover = new Hover();
                if ($partner->image) $hover->image($partner->uploadPath().$partner->image);

                return [
                    $partner->id,
                    $hover.$partner->title,
                    $partner->partnercat->title,
                ];            
            }
        ]);
    }

}