<?php namespace App\Modules\Forums\Http\Controllers;

use App\Modules\Forums\ForumReportCase;
use ModelHandlerTrait;
use Hover, HTML, BackController;

class AdminReportsController extends BackController {

    use ModelHandlerTrait;

    protected $icon = 'warning';

    public function __construct()
    {
        $this->modelName = 'ForumReportCase';

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'buttons'       => null,
            'dataSource'    => ForumReportCase::findAll(),
            'tableHead'     => [
                '#'                             => null,
                'Post ('.trans('app.text').')'  => null,
                'Forum Reports'                 => null,
                 trans('app.date')              => null,
            ],
            'tableRow' => function($forumReportCase)
            {
                $forumPost = $forumReportCase->post;

                $url = url('forums/posts/perma/'.$forumPost->id);
                $link = HTML::link($url, $forumPost->plainText(80));

                return [
                    $forumReportCase->index,
                    raw($link),
                    $forumReportCase->report_counter,
                    $forumReportCase->updated_at,
                ];            
            },
            'actions'       => ['delete'],
            'sortBy'        => 'level',
            'order'         => 'asc'
        ]);
    }

}