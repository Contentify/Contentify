<?php namespace App\Modules\Forums\Http\Controllers;

use App\Modules\Forums\Models\Forum;
use ModelHandlerTrait;
use Hover, HTML, BackController;

class AdminForumsController extends BackController {

    use ModelHandlerTrait { 
        create as traitCreate;
        edit as traitEdit;
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

}