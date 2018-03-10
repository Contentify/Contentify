<?php

namespace App\Modules\Downloads\Http\Controllers;

use App\Modules\Downloads\Downloadcat;
use ModelHandlerTrait;
use Hover, BackController;

class AdminDownloadcatsController extends BackController {

    use ModelHandlerTrait;

    protected $icon = 'folder';

    public function __construct()
    {
        $this->modelName = 'Downloadcat';

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'tableHead' => [
                trans('app.id') => 'id', 
                trans('app.title') => 'title'
            ],
            'tableRow' => function($downloadcat)
            {
                /** @var Downloadcat $downloadcat */
                Hover::modelAttributes($downloadcat, ['creator']);

                return [
                    $downloadcat->id,
                    raw(Hover::pull(), $downloadcat->title)
                ];
            }
        ]);
    }

}