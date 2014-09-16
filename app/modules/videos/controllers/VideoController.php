<?php namespace App\Modules\Videos\Controllers;

use App\Modules\Videos\Models\Video;
use Config, URL, FrontController;

class VideosController extends FrontController {

    public function __construct()
    {
        $this->modelName = 'Video';

        parent::__construct();
    }

    public function index()
    {
        $perPage = Config::get('app.frontItemsPerPage');

        $videos = Video::paginate($perPage);

        $this->pageView('videos::index', compact('videos'));
    }

    /**
     * Show a video
     * 
     * @param  int $id The id of the video
     * @return void
     */
    public function show($id)
    {
        $video = Video::findOrFail($id);

        $video->access_counter++;
        $video->save();

        $this->title($video->title);
        $video->openGraph($video->openGraph());

        $this->pageView('videos::show', compact('video'));
    }

    public function globalSearch($subject)
    {
        $videos = Video::where('title', 'LIKE', '%'.$subject.'%')->get();

        $results = array();
        foreach ($videos as $video) {
            $results[$video->title] = URL::to('videos/'.$video->id.'/show');
        }

        return $results;
    }

}