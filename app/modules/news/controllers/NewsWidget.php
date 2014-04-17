<?php namespace App\Modules\News\Controllers;

use App\Modules\News\Models\News;
use DB, View, Widget;

class NewsWidget extends Widget {

    public function render($parameters = array())
    {
        // Internal news are protected and require the "internal" permission:
        $hasAccess = (user() and user()->hasAccess('internal')); 
        $newsCollection = News::published()->where('internal', '<=', $hasAccess)
            ->orderBy('created_at', 'DESC')->take(5)->get();

        return View::make('news::widget', compact('newsCollection'))->render();
    }

}