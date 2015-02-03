<?php namespace App\Modules\Forums\Controllers;

use App\Modules\Forums\Models\Forum;
use FrontController;

class ForumsController extends FrontController {

    public function index()
    {
        $forums = Forum::with('forum')->hasAccess()->isRoot()->get();

        $this->pageView('forums::index', compact('forums'));
    }

    /**
     * Shows a forum
     *
     * @param  int  $id The ID of the forum
     * @return void
     */
    public function show($id)
    {
        $forum = Forum::with(array('threads' => function($query)
        {
            $query->orderBy('sticky', 'desc')->orderBy('updated_at', 'desc');

        }))->hasAccess()->findOrFail($id);

        $this->pageView('forums::show_forum', compact('forum'));
    }

}