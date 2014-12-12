<?php namespace App\Modules\Forums\Controllers;

use App\Modules\Forums\Models\Forum;
use Hover, HTML, BackController;

class AdminForumsController extends BackController {

    protected $icon = 'comment.png';

    public function __construct()
    {
        $this->modelName = 'Forum';

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'tableHead' => [
                trans('app.id')     => 'id', 
                trans('app.title')  => 'title'
            ],
            'tableRow' => function($forum)
            {
                $title = e($forum->title);
                if ($forum->level == 0) $title = '<strong>'.$title.'</strong>';

                return [
                    $forum->id,
                    raw(Hover::modelAttributes($forum, ['creator'])->pull().$title),
                ];            
            },
            'sortBy' => 'level',
            'order' => 'asc'
        ]);
    }

    public function create()
    {
        parent::create();

        $forums = Forum::all();      

        $this->layout->page->with('forums', $forums);
    }

    public function edit($id)
    {
        parent::edit($id);

        $forums = Forum::where('id', '!=', $id)->get();

        $this->layout->page->with('forums', $forums);
    }

}