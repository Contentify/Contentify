<?php 

namespace App\Modules\Streams\Http\Controllers;

use App\Modules\Streams\Stream;
use BackController;
use Hover;
use HTML;
use ModelHandlerTrait;

class AdminStreamsController extends BackController
{

    use ModelHandlerTrait;

    protected $icon = 'video';

    public function __construct()
    {
        $this->modelClass = Stream::class;

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
            'tableRow' => function(Stream $stream)
            {
                Hover::modelAttributes($stream, ['creator', 'updated_at']);

                return [
                    $stream->id,
                    raw(Hover::pull().HTML::link('streams/'.$stream->id.'/'.$stream->slug, $stream->title)),
                    Stream::$providers[$stream->provider],
                ];
            }
        ]);
    }

}
