<?php namespace App\Modules\Downloads\Http\Controllers;

use App\Modules\Downloads\Download;
use ModelHandlerTrait;
use Hover, BackController;

class AdminDownloadsController extends BackController {

    use ModelHandlerTrait;

    protected $icon = 'folder';

    public function __construct()
    {
        $this->modelName = 'Download';

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'buttons'   => ['new', 'category'],
            'tableHead' => [
                trans('app.id')         => 'id', 
                trans('app.title')      => 'title',
                trans('app.category')   => 'downloadcat_id'
            ],
            'tableRow' => function($download)
            {
                Hover::modelAttributes($download, ['access_counter', 'creator']);

                return [
                    $download->id,
                    raw(Hover::pull(), $download->title),
                    $download->downloadcat->title,
                ];            
            }
        ]);
    }

}