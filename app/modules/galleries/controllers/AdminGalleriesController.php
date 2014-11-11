<?php namespace App\Modules\Galleries\Controllers;

use App\Modules\Galleries\Models\Gallery;
use Hover, BackController;

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
                Hover::modelAttributes($gallery, ['access_counter', 'creator']);

                return [
                    $gallery->id,
                    raw(Hover::pull(), $gallery->title),
                ];            
            }
        ]);
    }

}