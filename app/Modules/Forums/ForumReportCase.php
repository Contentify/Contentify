<?php

namespace App\Modules\Forums;

use App\Modules\Forums\ForumReport;
use App\Modules\Forums\ForumPost;
use Carbon, DB, BaseModel;

class ForumReportCase extends BaseModel
{

    protected $fillable = ['index', 'post_id', 'report_counter'];

    /**
     * Returns an array with models for all existing modules
     * 
     * @return array
     */
    public static function findAll()
    {
        $results = DB::table('forum_reports')->select(DB::raw("*, COUNT('id') as report_counter"))
            ->orderBy('created_at', 'desc')->groupBy('post_id')->get();

        $forumReportCases = array();
        foreach ($results as $key => $result) {
            $data = [
                'index'             => $key + 1,
                'post_id'           => $result->post_id,
                'report_counter'    => $result->report_counter,
            ];

            $forumReportCase = new self($data);
            $forumReportCase->updated_at = $forumReportCase->asDateTime($result->updated_at);
            $forumReportCases[] = $forumReportCase;
        }

        return $forumReportCases;
    }

    public function post()
    {
        return $this->belongsTo('App\Modules\Forums\ForumPost');
    }

}