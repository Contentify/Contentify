<?php

namespace App\Modules\Countries\Http\Controllers;

use App\Modules\Countries\Country;
use ModelHandlerTrait;
use HTML, BackController;

class AdminCountriesController extends BackController
{

    use ModelHandlerTrait;

    protected $icon = 'flag';

    public function __construct()
    {
        $this->modelName = Country::class;

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'tableHead' => [
                trans('app.id')     => 'id',
                trans('app.icon')   => null,
                trans('app.title')  => 'title',
            ],
            'tableRow' => function($country)
            {
                /** @var Country $country */
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