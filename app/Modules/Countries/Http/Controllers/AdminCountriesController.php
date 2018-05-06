<?php

namespace App\Modules\Countries\Http\Controllers;

use App\Modules\Countries\Country;
use BackController;
use HTML;
use ModelHandlerTrait;

class AdminCountriesController extends BackController
{

    use ModelHandlerTrait;

    protected $icon = 'flag';

    public function __construct()
    {
        $this->modelClass = Country::class;

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'order' => 'asc',
            'tableHead' => [
                trans('app.id')     => 'id',
                trans('app.icon')   => null,
                trans('app.title')  => 'title',
            ],
            'tableRow' => function(Country $country)
            {
                if ($country->icon) {
                    $icon = HTML::image(asset($country->uploadPath().$country->icon), $country->title);
                } else{
                    $icon = null;
                }

                return [
                    $country->id,
                    raw($icon),
                    $country->title,
                ];
            }
        ]);
    }

}