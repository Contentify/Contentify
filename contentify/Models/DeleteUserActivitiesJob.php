<?php

namespace Contentify\Models;

use UserActivities, Job;

class DeleteUserActivitiesJob extends Job
{

    protected $timeSpan = 1440; // 60 minutes * 24 = 24h (once per day)

    public function run($executed)
    {
        UserActivities::deleteOld();
    }

}