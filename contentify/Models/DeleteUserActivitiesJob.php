<?php namespace Contentify\Models;

use UserActivities, Job;

class DeleteUserActivitiesJob extends Job {

    public function run($executed)
    {
        UserActivities::deleteOld();
    }

}