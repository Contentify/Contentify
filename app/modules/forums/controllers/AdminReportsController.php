<?php namespace App\Modules\Forums\Controllers;

use App\Modules\Forums\Models\Forum;
use Hover, HTML, BackController;

class AdminReportsController extends BackController {

    protected $icon = 'warning.png';

    public function __construct()
    {
        $this->modelName = 'ForumReport';

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'buttons' => null,
            'tableHead' => [
                trans('app.id')                 => 'id', 
                'Post ('.trans('app.text').')'  => null
            ],
            'tableRow' => function($forumReport)
            {
                $forumPost = $forumReport->post;
                
                $url = url('forums/threads/'.$forumPost->thread->id.'/'.$forumPost->thread->slug);
                $link = HTML::link($url, $forumPost->renderTextRaw());

                return [
                    $forumReport->id,
                    raw(Hover::modelAttributes($forumReport, ['creator'])->pull().$link),
                ];            
            },
            'actions' => ['delete'],
            'sortBy' => 'level',
            'order' => 'asc'
        ]);
    }

}