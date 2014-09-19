<?php namespace App\Modules\Downloads\Controllers;

use App\Modules\Downloads\Models\Download;
use Hover, BackController;

class AdminDownloadsController extends BackController {

    protected $icon = 'folder.png';

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
                return [
                    $download->id,
                    $download->title,
                    $download->downloadcat->title,
                ];            
            }
        ]);
    }

}