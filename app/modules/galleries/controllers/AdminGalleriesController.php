<?php namespace App\Modules\Galleries\Controllers;

use App\Modules\Galleries\Models\Gallery;
use BackController;

class AdminGalleriesController extends BackController {

    protected $icon = 'photo.png';

    public function __construct()
    {
        $this->modelName = 'Gallery';

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'tableHead' => [
                trans('app.id')     => 'id', 
                trans('app.title')  => 'title'
            ],
            'tableRow' => function($gallery)
            {
                return [
                    $gallery->id,
                    $gallery->title,
                ];            
            }
        ]);
    }

}