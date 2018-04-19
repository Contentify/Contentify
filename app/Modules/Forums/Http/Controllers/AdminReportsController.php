<?php

namespace App\Modules\Forums\Http\Controllers;

use App\Modules\Forums\ForumReport;
use App\Modules\Forums\ForumReportCase;
use BackController;
use HTML;
use ModelHandlerTrait;
use Redirect;
use UserActivities;

class AdminReportsController extends BackController
{

    use ModelHandlerTrait;

    protected $icon = 'exclamation-triangle';

    public function __construct()
    {
        $this->modelClass = ForumReportCase::class;

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'buttons'       => null,
            'dataSource'    => ForumReportCase::findAll(),
            'tableHead'     => [
                '#'                                                   => null,
                trans('app.object_post').' ('.trans('app.text').')'   => null,
                trans('app.object_reports')                           => null,
                trans('app.date')                                     => null,
            ],
            'tableRow' => function(ForumReportCase $forumReportCase)
            {
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

    /**
     * Delete all reports of the given post
     *
     * @param int $id The ID of the post
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function destroy($id)
    {
        if (! $this->checkAccessDelete()) {
            return null;
        }

        $forumReports = ForumReport::wherePostId($id)->get();

        foreach ($forumReports as $forumReport) {
            $forumReport->forceDelete(); // Finally delete this model
        }

        UserActivities::addDelete(false, user()->id, ForumReport::class);

        $this->alertFlash(trans('app.deleted', [trans_object('forum_report')]));
        return Redirect::route('admin.'.strtolower($this->getControllerName()).'.index');
    }

}