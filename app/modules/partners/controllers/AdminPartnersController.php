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
            'tableHead' => [
                trans('app.id')             => 'id', 
                trans('app.layout_type')    => 'layout_type', 
                trans('app.title')          => 'title'
            ],
            'tableRow' => function($partner)
            {
                $hover = new Hover();
                if ($partner->image) $hover->image($partner->uploadPath().$partner->image);

                return [
                    $partner->id,
                    $partner->layout_type,
                    $hover.$partner->title,
                ];            
            }
        ]);
    }

}