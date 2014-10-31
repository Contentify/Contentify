<?php namespace App\Modules\Countries\Controllers;

use App\Modules\Countries\Models\Country;
use HTML, File, BackController;

class AdminCountriesController extends BackController {

    protected $icon = 'flag_blue.png';

    public function __construct()
    {
        $this->modelName = 'Country';

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
                if ($country->icon) {
                    $icon = HTML::image(asset($country->uploadPath().$country->icon), $country->title);
                } else{
                    $icon = null;
                }

                return [
                    $country->id,
                    $icon,
                    $country->title,
                ];            
            }
        ]);
    }

}