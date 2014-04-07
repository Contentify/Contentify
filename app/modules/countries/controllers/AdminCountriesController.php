<?php namespace App\Modules\Countries\Controllers;

use App\Modules\Countries\Models\Country as Country;
use HTML, File, BackController;

class AdminCountriesController extends BackController {

    protected $icon = 'joystick.png';

    public function __construct()
    {
        $this->model = 'Country';

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
                $fileName = $country->uploadPath().$country->icon;
                if (File::exists($fileName)) {
                    $icon = HTML::image(asset($fileName), $country->title);
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