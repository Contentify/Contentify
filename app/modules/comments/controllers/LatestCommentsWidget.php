<?php namespace App\Modules\Comments\Controllers;

use Comment, View, Widget;

class LatestCommentsWidget extends Widget {

    public function render($parameters = array())
    {
        $comments = Comment::orderBy('created_at', 'DESC')->take(5)->get();

        return View::make('comments::widget_latest_comments', compact('comments'))->render();
    }

}