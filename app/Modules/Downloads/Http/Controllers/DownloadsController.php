<?php namespace App\Modules\Downloads\Http\Controllers;

use App\Modules\Downloads\Downloadcat;
use App\Modules\Downloads\Download;
use File, Response, Redirect, Request, Config, URL, FrontController;

class DownloadsController extends FrontController {

    public function __construct()
    {
        $this->modelName = 'Download';

        parent::__construct();
    }

    public function index()
    {
        $perPage = Config::get('app.frontItemsPerPage');

        // NOTE: Add has('downloads') to show only categories that have downloads
        $downloadcats = Downloadcat::paginate($perPage)->setPath(Request::url()); 

        $this->pageView('downloads::index', compact('downloadcats'));
    }

    /**
     * Show a category
     * 
     * @param  int $id The id of the category
     * @return void
     */
    public function showCategory($id)
    {
        $downloadcat = Downloadcat::findOrFail($id);

        $downloadcat->access_counter++;
        $downloadcat->save();

        $perPage = Config::get('app.frontItemsPerPage');
        $downloads = Download::whereDownloadcatId($id)->paginate($perPage)->setPath(Request::url()); 

        $this->title($downloadcat->title);

        $this->pageView('downloads::category', compact('downloadcat', 'downloads'));
    }

    /**
     * Show a download detail page
     * 
     * @param  int $id The id of the download
     * @return void
     */
    public function show($id)
    {
        $download = Download::findOrFail($id);

        $this->title($download->title);

        $this->pageView('downloads::show', compact('download'));
    }

    /**
     * Perform a download
     * 
     * @param  int $id The id of the download
     * @return void
     */
    public function perform($id)
    {
        $download = Download::findOrFail($id);

        $download->access_counter++;
        $download->save();

        $extension = File::extension($download->file);
        $shortName = $download->slug;
        if ($extension) $shortName .= '.'.$extension;
        return Response::download($download->uploadPath(true).$download->file, $shortName);
    }

    public function globalSearch($subject)
    {
        $downloads = Download::where('title', 'LIKE', '%'.$subject.'%')->get();

        $results = array();
        foreach ($downloads as $download) {
            $results[$download->title] = URL::to('downloads/'.$download->id.'/'.$download->slug);
        }

        return $results;
    }

}