<?php namespace App\Modules\News\Controllers;

use App\Modules\News\Models\News as News;
use View, Widget;

class NewsWidget extends Widget {

    public function render($parameters = array())
    {
        // Internal news are protected and require the "internal" permission:
        $hasAccess = (user() and user()->hasAccess('internal')); 
        $newsCollection = News::wherePublished(true)->where('internal', '<=', $hasAccess)
            ->orderBy('created_at', 'DESC')->take(5)->get();

        return View::make('news::widget', compact('newsCollection'))->render();
    }

}