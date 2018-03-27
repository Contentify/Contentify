<?php

namespace App\Modules\News\Http\Controllers;

use App\Modules\News\News;
use View;
use Widget;

class NewsWidget extends Widget
{

    public function render(array $parameters = array())
    {
        $limit = isset($parameters['limit']) ? (int) $parameters['limit'] : self::LIMIT;

        // Internal news are protected and require the "internal" permission:
        $hasAccess = (user() and user()->hasAccess('internal')); 
        $newsCollection = News::published()->where('internal', '<=', $hasAccess)
            ->orderBy('created_at', 'DESC')->take($limit)->get();

        return View::make('news::widget', compact('newsCollection'))->render();
    }

}