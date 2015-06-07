<?php namespace App\Modules\Galleries\Http\Controllers;

use App\Modules\Galleries\Models\Gallery;
use ModelHandlerTrait;
use Hover, BackController;

class AdminGalleriesController extends BackController {

    use ModelHandlerTrait;

    protected $icon = 'file-photo-o';

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