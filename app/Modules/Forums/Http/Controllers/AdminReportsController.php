<?php

namespace App\Modules\Forums\Http\Controllers;

use App\Modules\Forums\ForumPost;
use App\Modules\Forums\ForumReportCase;
use ModelHandlerTrait;
use HTML, BackController;

class AdminReportsController extends BackController
{

    use ModelHandlerTrait;

    protected $icon = 'exclamation-triangle';

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
                '#'                                                     => null,
                trans('app.object_post').' ('.trans('app.text').')'     => null,
                trans('app.object_reports')                             => null,
                trans('app.date')                                       => null,
            ],
            'tableRow' => function($forumReportCase)
            {
                /** @var ForumPost $forumPost */
                $forumPost = $forumReportCase->post;

                $link = HTML::link($forumPost->paginatedPostUrl(), $forumPost->plainText(80));

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