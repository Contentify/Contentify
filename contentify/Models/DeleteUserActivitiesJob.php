<?php

namespace Contentify\Models;

use AbstractJob;
use UserActivities;

class DeleteUserActivitiesJob extends AbstractJob
{

    protected $interval  = 1440; // 60 minutes * 24 = 24h (once per day)

    /**
     * {@inheritdoc}
     */
    public function run($executedAt)
    {
        UserActivities::deleteOld();
    }

}