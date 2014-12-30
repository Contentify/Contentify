<?php namespace App\Modules\Downloads\Controllers;

use App\Modules\Downloads\Models\Downloadcat;
use ModelHandlerTrait;
use Hover, BackController;

class AdminDownloadcatsController extends BackController {

    use ModelHandlerTrait;

    protected $icon = 'folder.png';

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
                Hover::modelAttributes($downloadcat, ['creator']);

                return [
                    $downloadcat->id,
                    raw(Hover::pull(), $downloadcat->title)
                ];
            }
        ]);
    }

}