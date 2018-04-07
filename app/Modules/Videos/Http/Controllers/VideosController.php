<?php 

namespace App\Modules\Videos\Http\Controllers;

use App\Modules\Videos\Video;
use Config;
use Contentify\GlobalSearchInterface;
use FrontController;
use URL;

class VideosController extends FrontController implements GlobalSearchInterface
{

    public function __construct()
    {
        $this->modelClass = Video::class;

        parent::__construct();
    }

    public function index()
    {
        $perPage = Config::get('app.frontItemsPerPage');

        $videos = Video::orderBy('created_at', 'DESC')->paginate($perPage);

        $this->pageView('videos::index', compact('videos'));
    }

    /**
     * Show a video
     * 
     * @param int $id The ID of the video
     * @return void
     */
    public function show($id)
    {
        /** @var Video $video */
        $video = Video::findOrFail($id);

        $video->access_counter++;
        $video->save();

        $this->title($video->title);
        $this->openGraph($video->openGraph());

        $this->pageView('videos::show', compact('video'));
    }

    /**
     * This method is called by the global search (SearchController->postCreate()).
     * Its purpose is to return an array with results for a specific search query.
     * 
     * @param string $subject The search term
     * @return string[]
     */
    public function globalSearch($subject)
    {
        $videos = Video::where('title', 'LIKE', '%'.$subject.'%')->get();

        $results = array();
        foreach ($videos as $video) {
            $results[$video->title] = URL::to('videos/'.$video->id.'/'.$video->slug);
        }

        return $results;
    }

}