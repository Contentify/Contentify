<?php

namespace App\Modules\Downloads\Http\Controllers;

use App\Modules\Downloads\Downloadcat;
use BackController;
use Hover;
use ModelHandlerTrait;

class AdminDownloadcatsController extends BackController
{

    use ModelHandlerTrait;

    protected $icon = 'folder';

    public function __construct()
    {
        $this->modelClass = Downloadcat::class;

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'tableHead' => [
                trans('app.id') => 'id', 
                trans('app.title') => 'title'
            ],
            'tableRow' => function(Downloadcat $downloadcat)
            {
                Hover::modelAttributes($downloadcat, ['creator']);

                return [
                    $downloadcat->id,
                    raw(Hover::pull(), $downloadcat->title)
                ];
            }
        ]);
    }

}