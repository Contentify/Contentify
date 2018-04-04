<?php

namespace App\Modules\Forums;

use BaseModel;
use DB;

/**
 * @property int index
 * @property int $post_id
 * @property int $report_counter
 * @property int $creator_id
 * @property int $updater_id
 * @property \App\Modules\Forums\ForumPost $post
 */
class ForumReportCase extends BaseModel
{

    protected $fillable = ['id', 'index', 'post_id', 'report_counter'];

    public function post()
    {
        return $this->belongsTo('App\Modules\Forums\ForumPost', 'post_id');
    }

    /**
     * Returns an array with bag objects for all existing reports
     * 
     * @return ForumReportCase[]
     */
    public static function findAll()
    {
        $results = DB::table('forum_reports')
            ->select(DB::raw("post_id, COUNT(id) as report_counter, MAX(updated_at) AS updated_at"))
            ->orderBy('created_at', 'desc')
            ->groupBy('post_id')->get();

        $forumReportCases = array();
        foreach ($results as $key => $result) {
            $data = [
                'id'                => $result->post_id, // Use the ID of the post as as pseudo ID for the delete button
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

}