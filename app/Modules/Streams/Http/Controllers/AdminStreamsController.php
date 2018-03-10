<?php 

namespace App\Modules\Streams\Http\Controllers;

use ModelHandlerTrait;
use App\Modules\Streams\Stream;
use Hover, HTML, BackController;

class AdminStreamsController extends BackController {

    use ModelHandlerTrait;

    protected $icon = 'video';

    public function __construct()
    {
        $this->modelName = 'Stream';

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
            'tableRow' => function($stream)
            {
                Hover::modelAttributes($stream, ['creator']);

                return [
                    $stream->id,
                    raw(Hover::pull().HTML::link('streams/'.$stream->id.'/'.$stream->slug, $stream->title)),
                    Stream::$providers[$stream->provider],
                ];             
            }
        ]);
    }

}