<?php 

namespace App\Modules\Videos\Http\Controllers;

use App\Modules\Videos\Video;
use BackController;
use Hover;
use HTML;
use ModelHandlerTrait;

class AdminVideosController extends BackController 
{

    use ModelHandlerTrait;

    protected $icon = 'youtube';

    public function __construct()
    {
        $this->modelClass = Video::class;

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
            'tableRow' => function(Video $video)
            {
                Hover::modelAttributes($video, ['creator', 'updated_at']);

                return [
                    $video->id,
                    raw(Hover::pull().HTML::link('videos/'.$video->id.'/'.$video->slug, $video->title)),
                    Video::$providers[$video->provider],
                ];
            }
        ]);
    }

}
