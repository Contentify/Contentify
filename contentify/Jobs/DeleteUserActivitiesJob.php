<?php

namespace Contentify\Jobs;

use AbstractJob;
use UserActivities;

class DeleteUserActivitiesJob extends AbstractJob
{

    /**
     * {@inheritdoc}
     */
    protected $interval  = 1440; // 60 minutes * 24 = 24h (once per day)

    /**
     * {@inheritdoc}
     */
    public function run($executedAt)
    {
        UserActivities::deleteOld();
    }

}