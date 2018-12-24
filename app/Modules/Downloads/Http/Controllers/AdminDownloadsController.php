<?php

namespace App\Modules\Downloads\Http\Controllers;

use App\Modules\Downloads\Download;
use BackController;
use Hover;
use HTML;
use ModelHandlerTrait;

class AdminDownloadsController extends BackController
{

    use ModelHandlerTrait;

    protected $icon = 'folder';

    public function __construct()
    {
        $this->modelClass = Download::class;

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'buttons'   => ['new', 'category'],
            'tableHead' => [
                trans('app.id')         => 'id',
                trans('app.published')  => 'published',
                trans('app.title')      => 'title',
                trans('app.category')   => 'download_cat_id'
            ],
            'tableRow' => function(Download $download)
            {
                Hover::modelAttributes($download, ['access_counter', 'creator', 'updated_at']);

                return [
                    $download->id,
                    raw($download->published ? HTML::fontIcon('check') : HTML::fontIcon('times')),
                    raw(Hover::pull().HTML::link('downloads/'.$download->id.'/'.$download->slug, $download->title)),
                    $download->downloadCat->title,
                ];
            }
        ]);
    }

}
