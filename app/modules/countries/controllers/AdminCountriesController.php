<?php namespace App\Modules\Countries\Controllers;

use App\Modules\Countries\Models\Country as Country;
use File, Hover, BackController;

class AdminCountriesController extends BackController {

    protected $icon = 'joystick.png';

    public function __construct()
    {
        $this->model = 'Country';

        parent::__construct();
    }

    public function index()
    {
        $this->buildIndexPage([
            'tableHead' => [
                t('ID')     => 'id', 
                t('Title')  => 'title'
            ],
            'tableRow' => function($country)
            {
                $hover = new Hover();
                $fileName = 'icons/flags/'.$country->code.'.png';
                if (File::exists($fileName)) $hover->image(asset($fileName));

                return [
                    $country->id,
                    $hover.$country->title,
                ];            
            }
        ]);
    }

}