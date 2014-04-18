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
                trans('app.id')     => 'id', 
                trans('app.type')   => 'type', 
                trans('app.title')  => 'title'
            ],
            'tableRow' => function($partner)
            {
                $hover = new Hover();
                if ($partner->icon) $hover->image($partner->uploadPath().$partner->icon);

                return [
                    $partner->id,
                    $partner->type,
                    $hover.$partner->title,
                ];            
            }
        ]);
    }

}