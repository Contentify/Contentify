<?php

namespace App\Modules\News\Http\Controllers;

use App\Modules\News\News;
use App\Modules\Videos\Video;
use Contentify\GlobalSearchInterface;
use DB;
use FrontController;
use HTML;
use Illuminate\Database\Eloquent\Builder;
use Request;
use URL;
use View;

class NewsController extends FrontController implements GlobalSearchInterface
{

    public function __construct()
    {
        $this->modelClass = News::class;

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'buttons'       => null,
            'tableHead'     => [
                trans('app.title')      => 'title', 
                trans('app.category')   => 'news_cat_id',
                trans('app.date')       => 'created_at'
            ],
            'tableRow'      => function(News $news)
            {
                return [
                    raw(HTML::link(url('news/'.$news->id.'/'.$news->slug), $news->title)),
                    $news->newsCat->title,
                    $news->created_at
                ];
            },
            'actions'       => null,
            'filter'        => true,
            'permaFilter'   => function(Builder $query)
            {
                return $query->published();
            }
        ], 'front');
    }

    /**
     * Show the preview of multiple news
     *
     * @return void
     * @throws \Exception
     */
    public function showOverview()
    {
        // Internal news are protected and require the "internal" permission:
        $hasAccess = (user() and user()->hasAccess('internal'));
        $newsCollection = News::published()->where('internal', '<=', $hasAccess)->filter()
            ->orderBy('created_at', 'DESC')->paginate(5);

        $this->pageView('news::show_overview', compact('newsCollection'));
    }

    /**
     * Shows a "stream" of "news" (or in generally: generated content) with news and videos
     *
     * @param int|string|null $offset Null or a timestamp
     * @return \Illuminate\Contracts\View\View|null
     * @throws \Exception
     */
    public function showStream($offset = null)
    {
        if ($offset) {
            $offset = (int) $offset;
        } else {
            $offset = time();
        }
        $offset = DB::raw('FROM_UNIXTIME('.$offset.')');

        $columns = 2;
        $rows = 3;
        $limit = $columns * $rows;
        $streamItems = [];

        /*
         * News
         */
        $hasAccess = (user() and user()->hasAccess('internal')); // Internal news are protected
        $newsCollection = News::published()->where('internal', '<=', $hasAccess)->where('updated_at', '<', $offset)
            ->orderBy('updated_at', 'DESC')->take($limit)->get();

        foreach ($newsCollection as $news) {
            $news->itemType = 'news';
            $streamItems[] = $news;
        }

        /*
         * Videos
         */
        $videos = Video::where('updated_at', '<', $offset)->orderBy('updated_at', 'DESC')->take($limit)->get();

        foreach ($videos as $video) {
            $video->itemType = 'video';
            $streamItems[] = $video;
        }

        /*
         * Sort the stream.
         * ATTENTION: All items need to have the updated_at timestamp attribute!
         */
        usort($streamItems, function($itemOne, $itemTwo) 
        {
            return ($itemOne->updated_at->timestamp > $itemTwo->updated_at->timestamp) ? -1 : 1;
        });

        $oldSize = sizeof($streamItems);
        $streamItems = array_slice($streamItems, 0, $limit);
        $more = (int) ($oldSize > sizeof($streamItems));

        if (Request::ajax()) {
            return View::make('news::show_stream_ajax', compact('streamItems', 'more'));
        } else {
            $this->pageView('news::show_stream', compact('streamItems', 'more', 'limit'));
            return null;
        }
    }

    /**
     * Show a news
     *
     * @param  int    $id   The ID of the news
     * @param  string $slug The unique slug (optional)
     * @return void
     * @throws \Exception
     */
    public function show($id, $slug = null)
    {
        /** @var News $news */
        if ($slug) {
            $news = News::whereSlug($slug)->published()->firstOrFail();
        } else {
            $news = News::whereId($id)->published()->firstOrFail();
        }

        $hasAccess = (user() and user()->hasAccess('internal'));
        if ($news->internal and ! $hasAccess) {
            $this->alertError(trans('app.access_denied'));
            return;
        }

        $news->access_counter++;
        $news->save();

        $this->title($news->title);
        $this->openGraph($news->openGraph());

        $this->pageView('news::show', compact('news'));
    }

    /**
     * Show a news by slug instead of ID
     *
     * @param  string $slug The unique slug
     * @return void
     * @throws \Exception
     */
    public function showBySlug($slug)
    {
        $this->show(null, $slug);
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
        $newsCollection = News::published()->where('title', 'LIKE', '%'.$subject.'%')->get();

        $results = array();
        foreach ($newsCollection as $news) {
            $results[$news->title] = URL::to('news/'.$news->id.'/show');
        }

        return $results;
    }

}
