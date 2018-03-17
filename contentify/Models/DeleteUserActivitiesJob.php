<?php

namespace Contentify\Models;

use UserActivities, AbstractJob;

class DeleteUserActivitiesJob extends AbstractJob
{

    protected $interval  = 1440; // 60 minutes * 24 = 24h (once per day)

    public function run($executedAt)
    {
        UserActivities::deleteOld();
    }

}