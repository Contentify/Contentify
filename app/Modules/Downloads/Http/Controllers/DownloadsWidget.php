<?php namespace App\Modules\Downloads\Http\Controllers;

use App\Modules\Downloads\Download;
use View, Widget;

class DownloadsWidget extends Widget {

    public function render($parameters = array())
    {
        $downloads = Download::orderBy('created_at', 'DESC')->take(5)->get();

        return View::make('downloads::widget', compact('downloads'))->render();
    }

}