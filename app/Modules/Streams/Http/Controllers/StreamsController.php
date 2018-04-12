<?php 

namespace App\Modules\Streams\Http\Controllers;

use App\Modules\Streams\Stream;
use Config;
use Contentify\GlobalSearchInterface;
use FrontController;
use HTML;
use URL;

class StreamsController extends FrontController implements GlobalSearchInterface
{

    public function __construct()
    {
        $this->modelClass = Stream::class;

        parent::__construct();
    }

    public function index()
    {
        $perPage = Config::get('app.frontItemsPerPage');

        $streams = Stream::orderBy('online', 'desc')->paginate($perPage);

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
            'tableRow'  => function(Stream $stream)
            {
                return [
                    raw(HTML::link(url('streams/'.$stream->id.'/'.$stream->slug), $stream->title)),
                    Stream::$providers[$stream->provider],
                    raw($stream->online ? HTML::fontIcon('check') : HTML::fontIcon('times')),
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
     * @param  int $id The ID of the stream
     * @return void
     */
    public function show($id)
    {
        /** @var Stream $stream */
        $stream = Stream::findOrFail($id);

        $stream->access_counter++;
        $stream->save();

        $this->title($stream->title);

        $this->pageView('streams::show', compact('stream'));
    }
    
    /**
     * This method is called by the global search (SearchController->postCreate()).
     * Its purpose is to return an array with results for a specific search query.
     * 
     * @param  string $subject The search term
     * @return string[]
     */
    public function globalSearch($subject)
    {
        $streams = Stream::where('title', 'LIKE', '%'.$subject.'%')->get();

        $results = array();
        foreach ($streams as $stream) {
            $results[$stream->title] = URL::to('streams/'.$stream->id.'/'.$stream->slug);
        }

        return $results;
    }

}