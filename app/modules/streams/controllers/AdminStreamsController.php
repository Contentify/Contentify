<?php namespace App\Modules\Streams\Controllers;

use ModelHandlerTrait;
use App\Modules\Streams\Models\Stream;
use Hover, HTML, BackController;

class AdminStreamsController extends BackController {

    use ModelHandlerTrait;

    protected $icon = 'film_link.png';

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
                    raw(Hover::pull(), $stream->title),
                    Stream::$providers[$stream->provider],
                ];             
            }
        ]);
    }

}