<?php

namespace App\Modules\Forums\Http\Controllers;

use App\Modules\Forums\ForumThread;
use View;
use Widget;

class LatestThreadsWidget extends Widget
{

    public function render(array $parameters = array())
    {
        $limit = isset($parameters['limit']) ? (int) $parameters['limit'] : self::LIMIT;

        $forumThreads = ForumThread::isAccessible()->orderBy('forum_threads.updated_at', 'DESC')->take($limit)->get();

        return View::make('forums::widget_latest_threads', compact('forumThreads'))->render();
    }

}