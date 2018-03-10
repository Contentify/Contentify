<?php

namespace App\Modules\Downloads\Http\Controllers;

use App\Modules\Downloads\Download;
use View, Widget;

class DownloadsWidget extends Widget {

    public function render($parameters = array())
    {
        $limit = isset($parameters['limit']) ? (int) $parameters['limit'] : self::LIMIT;

        $downloads = Download::orderBy('created_at', 'DESC')->take($limit)->get();

        return View::make('downloads::widget', compact('downloads'))->render();
    }

}