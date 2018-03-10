<?php

namespace App\Modules\Galleries\Http\Controllers;

use App\Modules\Galleries\Gallery;
use ModelHandlerTrait;
use Hover, HTML, BackController;

class AdminGalleriesController extends BackController {

    use ModelHandlerTrait;

    protected $icon = 'file-image';

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
                /** @var Gallery $gallery */
                Hover::modelAttributes($gallery, ['access_counter', 'creator']);

                return [
                    $gallery->id,
                    raw(Hover::pull().HTML::link('galleries/'.$gallery->id, $gallery->title)),
                ];            
            }
        ]);
    }

}