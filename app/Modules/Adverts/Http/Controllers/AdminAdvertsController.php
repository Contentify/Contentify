<?php

namespace App\Modules\Adverts\Http\Controllers;

use App\Modules\Adverts\Advert;
use ModelHandlerTrait;
use HTML, Hover, BackController;

class AdminAdvertsController extends BackController
{

    use ModelHandlerTrait;

    protected $icon = 'bullhorn';

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
                trans('app.published')      => 'published', 
                trans('app.title')          => 'title',
                trans('app.category')       => 'advertcat_id', 
            ],
            'tableRow' => function($advert)
            {
                /** @var Advert $advert */
                Hover::modelAttributes($advert, ['image', 'access_counter', 'creator']);

                return [
                    $advert->id,
                    raw($advert->published ? HTML::fontIcon('check') : HTML::fontIcon('times')),
                    raw(Hover::pull(), $advert->title),
                    $advert->advertcat->title,
                ];            
            }
        ]);
    }

}