<?php

namespace App\Modules\Downloads\Http\Controllers;

use App\Modules\Downloads\DownloadCat;
use BackController;
use Hover;
use ModelHandlerTrait;

class AdminDownloadCatsController extends BackController
{

    use ModelHandlerTrait;

    protected $icon = 'folder';

    public function __construct()
    {
        $this->modelClass = DownloadCat::class;

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'tableHead' => [
                trans('app.id') => 'id', 
                trans('app.title') => 'title'
            ],
            'tableRow' => function(DownloadCat $downloadCat)
            {
                Hover::modelAttributes($downloadCat, ['creator', 'updated_at']);

                return [
                    $downloadCat->id,
                    raw(Hover::pull(), $downloadCat->title)
                ];
            }
        ]);
    }

}
