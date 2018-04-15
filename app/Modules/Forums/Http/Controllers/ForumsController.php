<?php

namespace App\Modules\Forums\Http\Controllers;

use App\Modules\Forums\Forum;
use FrontController;
use Illuminate\Database\Eloquent\Relations\Relation;

class ForumsController extends FrontController
{

    public function index()
    {
        $forums = Forum::with('forum')->isAccessible()->isRoot()->get();

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
        /** @var Forum $forum */
        $forum = Forum::with(array('threads' => function(Relation $query)
        {
            $query->orderBy('sticky', 'desc')->orderBy('updated_at', 'desc');
        }))->isAccessible()->findOrFail($id);

        $this->title($forum->title);

        $this->pageView('forums::show_forum', compact('forum'));
    }

}