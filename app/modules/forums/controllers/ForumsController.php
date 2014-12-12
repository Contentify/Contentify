<?php namespace App\Modules\Forums\Controllers;

use App\Modules\Forums\Models\Forum;
use FrontController;

class ForumsController extends FrontController {

    public function index()
    {
        $forums = Forum::with('forum')->isRoot()->get();

        $this->pageView('forums::index', compact('forums'));
    }

    /**
     * Show a forum
     *
     * @param  int  $id The ID of the forum
     * @return void
     */
    public function show($id)
    {
        $forum = Forum::with(array('threads' => function($query)
        {
            $query->orderBy('sticky', 'desc')->orderBy('updated_at', 'desc');

        }))->findOrFail($id);

        $this->pageView('forums::show_forum', compact('forum'));
    }

}