<?php namespace Contentify\Models;

use UserActivities, Job;

class DeleteUserActivitiesJob extends Job {

	protected $timeSpan = 86400; // 24h (once per day)

    public function run($executed)
    {
        UserActivities::deleteOld();
    }

}