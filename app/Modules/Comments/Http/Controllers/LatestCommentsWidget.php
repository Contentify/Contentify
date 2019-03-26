<?php

namespace App\Modules\Comments\Http\Controllers;

use Comment;
use View;
use Widget;

class LatestCommentsWidget extends Widget
{

    public function render(array $parameters = []) : string
    {
        $comments = Comment::orderBy('created_at', 'DESC')->take(5)->get();

        return View::make('comments::widget_latest_comments', compact('comments'))->render();
    }
}
