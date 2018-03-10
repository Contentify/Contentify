<?php

namespace App\Modules\Forums\Http\Controllers;

use App\Modules\Forums\Forum;
use ModelHandlerTrait;
use Hover, HTML, BackController;

class AdminForumsController extends BackController {

    use ModelHandlerTrait { 
        create as traitCreate;
        edit as traitEdit;
        update as traitUpdate;
    }

    protected $icon = 'comment';

    public function __construct()
    {
        $this->modelName = 'Forum';

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'buttons'   => ['new', 'config'],
            'tableHead' => [
                trans('app.id')     => 'id', 
                trans('app.title')  => 'title'
            ],
            'tableRow' => function($forum)
            {
                $title  = e($forum->title);
                $url    = url('forums');

                if ($forum->level == 0) {
                    $title = '<strong>'.$title.'</strong>';
                } else {
                    $url .= '/'.$forum->id.'/'.$forum->slug;
                }

                $link = '<a href="'.$url.'">'.$title.'</a>';

                return [
                    $forum->id,
                    raw(Hover::modelAttributes($forum, ['creator'])->pull().$link),
                ];            
            },
            'sortBy' => 'level',
            'order' => 'asc'
        ]);
    }

    public function create()
    {
        $this->traitCreate();

        $forums = Forum::all();

        $this->layout->page->with('forums', $forums);
    }

    public function edit($id)
    {
        $this->traitEdit($id);

        $forums = Forum::where('id', '!=', $id)->get();

        $this->layout->page->with('forums', $forums);
    }

    public function update($id)
    {
        $forum = Forum::findOrFail($id);

        $oldParentId = $forum->forum_id;

        $response = $this->traitUpdate($id);

        $forum = Forum::findOrFail($id);

        /*
         * If the forum's parent forum changed we have to refresh
         * the old and the new parent forum's meta infos
         */
        if ($forum->forum_id != $oldParentId) {
            $oldParentForum = Forum::find($oldParentId);
            $oldParentForum->refresh();

            $forum->forum->refresh();
        }

        return $response;
    }

}