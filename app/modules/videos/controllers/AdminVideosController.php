<?php namespace App\Modules\Videos\Controllers;

use App\Modules\Videos\Models\Video;
use BackController;

class AdminVideosController extends BackController {

    protected $icon = 'film.png';

    public function __construct()
    {
        $this->modelName = 'Video';

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'tableHead' => [
                trans('app.id')         => 'id', 
                trans('app.title')      => 'title',
                trans('app.provider')   => 'provider',
            ],
            'tableRow' => function($video)
            {
                return [
                    $video->id,
                    $video->title,
                    Video::$providers[$video->provider],
                ];            
            }
        ]);
    }

}