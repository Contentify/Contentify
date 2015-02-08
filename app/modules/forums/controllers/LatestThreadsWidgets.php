<?php namespace App\Modules\Forums\Controllers;

use App\Modules\Forums\Models\ForumThread;
use View, Widget;

class LatestThreadsWidget extends Widget {

    public function render($parameters = array())
    {
        $forumThreads = ForumThread::isAccessible()->orderBy('forum_threads.updated_at', 'DESC')->take(5)->get();

        return View::make('forums::widget_latest_threads', compact('forumThreads'))->render();
    }

}