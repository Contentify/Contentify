<?php namespace App\Modules\Adverts\Controllers;

use App\Modules\Adverts\Models\Advert;
use Hover, BackController;

class AdminAdvertsController extends BackController {

    protected $icon = 'money.png';

    public function __construct()
    {
        $this->modelName = 'Advert';

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
            'tableRow' => function($advert)
            {
                $hover = new Hover();
                if ($advert->image) $hover->image($advert->uploadPath().$advert->image);

                return [
                    $advert->id,
                    $advert->layout_type,
                    $hover.$advert->title,
                ];            
            }
        ]);
    }

}