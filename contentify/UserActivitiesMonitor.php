<?php namespace Contentify;

use Contentify\Models\UserActivity; // TODO: Use alias instead of namespace!
use DB;

class ActivitiesMonitor {

    /*
     * User activities are not the same as permissions!
     * It's possible to create activity constants that are not equal
     * to a permission constant.
     * Nevertheless it makes sense to use the permission constants here.
     */
    const ACTIVITY_CREATE = PERM_CREATE;

    const ACTIVITY_READ = PERM_READ;

    const ACTIVITY_UPDATE = PERM_UPDATE;

    const ACTIVITY_DELETE = PERM_DELETE;

    public function add($activityId, $frontend, $userId, $info = null, $createdAt = null)
    {
        if (! $createdAt) {
            $createdAt = time();
        }

        $data = [
            'activity_id'   => $activityId, 
            'frontend'      => $frontend, 
            'user_id'       => $userId, 
            'info'          => $info,
            'created_at'    => $createdAt,
        ];
        
        $userActivity = new UserActivity($data);
    }

    public function addCreate($frontend, $userId, $info = null)
    {
        $this->add(self::ACTIVITY_CREATE, $frontend, $userId, $info);
    }

    public function addRead($frontend, $userId, $info = null)
    {
        $this->add(self::ACTIVITY_READ, $frontend, $userId, $info);
    }

    public function addUpdate($frontend, $userId, $info = null)
    {
        $this->add(self::ACTIVITY_UPDATE, $frontend, $userId, $info);
    }

    public function addDelete($frontend, $userId, $info = null)
    {
        $this->add(self::ACTIVITY_DELETE, $frontend, $userId, $info);
    }

    public function getByActivity($activityId)
    {
        return UserActivity::whereActivityId($activityId)->get();
    }

    public function getByInterface($frontend)
    {
        return UserActivity::whereFrontend(true)->get();
    }

    public function getByUser($userId)
    {
        return UserActivity::whereUserId($userId)->get();
    }

}

