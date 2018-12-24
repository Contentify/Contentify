<?php

namespace App\Modules\Galleries\Http\Controllers;

use App\Modules\Galleries\Gallery;
use BackController;
use Hover;
use HTML;
use ModelHandlerTrait;

class AdminGalleriesController extends BackController
{

    use ModelHandlerTrait;

    protected $icon = 'file-image';

    public function __construct()
    {
        $this->modelClass = Gallery::class;

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'buttons'   => ['new', HTML::button(trans('app.object_images'), url('admin/images'), 'image')],
            'tableHead' => [
                trans('app.id')         => 'id',
                trans('app.published')  => 'published',
                trans('app.title')      => 'title'
            ],
            'tableRow' => function(Gallery $gallery)
            {
                Hover::modelAttributes($gallery, ['access_counter', 'creator', 'updated_at']);

                return [
                    $gallery->id,
                    raw($gallery->published ? HTML::fontIcon('check') : HTML::fontIcon('times')),
                    raw(Hover::pull().HTML::link('galleries/'.$gallery->id, $gallery->title)),
                ];
            }
        ]);
    }

}
