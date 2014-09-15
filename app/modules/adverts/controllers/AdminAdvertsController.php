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
            'buttons'   => ['new', 'category'],
            'tableHead' => [
                trans('app.id')             => 'id', 
                trans('app.title')          => 'title',
                trans('app.category')       => 'advertcat_id', 
            ],
            'tableRow' => function($advert)
            {
                $hover = new Hover();
                if ($advert->image) $hover->image($advert->uploadPath().$advert->image);

                return [
                    $advert->id,
                    $hover.$advert->title,
                    $advert->advertcat->title,
                ];            
            }
        ]);
    }

}