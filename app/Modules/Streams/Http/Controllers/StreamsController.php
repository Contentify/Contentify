<?php namespace App\Modules\Streams\Http\Controllers;

use App\Modules\Streams\Stream;
use Request, HTML, Config, URL, FrontController;

class StreamsController extends FrontController {

    public function __construct()
    {
        $this->modelName = 'Stream';

        parent::__construct();
    }

    public function index()
    {
        $perPage = Config::get('app.frontItemsPerPage');

        $streams = Stream::orderBy('online', 'desc')->paginate($perPage)->setPath(Request::url());

        $this->pageView('streams::index', compact('streams'));
    }

    /**
     * Displays a page with a simple table with all streams
     * 
     * @return void
     */
    public function indexSimple()
    {
        $this->indexPage([
            'buttons'   => null,
            'tableHead' => [
                trans('app.title')      => 'title', 
                trans('app.category')   => 'provider', 
                trans('app.online')     => 'online',
                trans('app.viewers')    => 'viewers',
            ],
            'tableRow'  => function($stream)
            {
                return [
                    raw(HTML::link(url('streams/'.$stream->id.'/'.$stream->slug), $stream->title)),
                    Stream::$providers[$stream->provider],
                    raw($stream->online ? 
                        HTML::fontIcon('check') :
                        HTML::fontIcon('close')),
                    $stream->viewers,
                ];
            },
            'actions'   => null,
            'filter'    => true
        ], 'front');
    }

    /**
     * Show a stream
     * 
     * @param  int $id The id of the stream
     * @return void
     */
    public function show($id)
    {
        $stream = Stream::findOrFail($id);

        $stream->access_counter++;
        $stream->save();

        $this->title($stream->title);

        $this->pageView('streams::show', compact('stream'));
    }

}