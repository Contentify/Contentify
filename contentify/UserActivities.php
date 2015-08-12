<?php namespace Contentify;

use Contentify\Models\UserActivity;
use Exception, DB;

class UserActivities {

    /*
     * NOTE: User activities are not the same as permissions!
     * It's possible to create activity constants that are not equal
     * to a permission constant.
     * Nevertheless it makes sense to use the permission constants here.
     */
    const ACTIVITY_CREATE = PERM_CREATE;

    const ACTIVITY_READ = PERM_READ;

    const ACTIVITY_UPDATE = PERM_UPDATE;

    const ACTIVITY_DELETE = PERM_DELETE;

    /**
     * Add an actitivty
     * 
     * @param int       $activityId The ID of the activity - use one of the provided constants!
     * @param bool      $frontend   The interface  - true = frontend, false = backend
     * @param int       $userId     The ID of the related user (usually user()->id)
     * @param string    $modelClass The name of the model class (with namespace!)
     * @param string    $info       Additional information
     * @param int       $createdAt  Date and time when the activity was created. Null = now.
     * @return void
     */
    public function add($activityId, $frontend, $userId, $modelClass = null, $info = null, $createdAt = null)
    {
        if (! $createdAt) {
            $createdAt = time();
        }

        $data = [
            'activity_id'   => $activityId, 
            'frontend'      => $frontend, 
            'user_id'       => $userId, 
            'model_class'   => $modelClass, 
            'info'          => $info,
            'created_at'    => $createdAt,
        ];
        
        $userActivity = new UserActivity($data);
        $okay = $userActivity->save();

        if (! $okay) {
            throw new Exception("UserActivities: Can't create UserActivity model - validation failed.");
        }
    }

    public function addCreate($frontend, $userId, $modelClass = null, $info = null, $createdAt = null)
    {
        $this->add(self::ACTIVITY_CREATE, $frontend, $userId, $modelClass, $info, $createdAt);
    }

    public function addRead($frontend, $userId, $modelClass = null, $info = null, $createdAt = null)
    {
        $this->add(self::ACTIVITY_READ, $frontend, $userId, $modelClass, $info, $createdAt);
    }

    public function addUpdate($frontend, $userId, $modelClass = null, $info = null, $createdAt = null)
    {
        $this->add(self::ACTIVITY_UPDATE, $frontend, $userId, $modelClass, $info, $createdAt);
    }

    public function addDelete($frontend, $userId, $modelClass = null, $info = null, $createdAt = null)
    {
        $this->add(self::ACTIVITY_DELETE, $frontend, $userId, $modelClass, $info, $createdAt);
    }

    /**
     * Receives all activies with a given acitivity ID
     * 
     * @param  int $activityId The ID of the activity - use one of the provided constants!
     * @return UserActivity
     */
    public function getByActivity($activityId)
    {
        return UserActivity::whereActivityId($activityId)->get();
    }

    /**
     * Receives all activies with a given interface (frontend / backend)
     * 
     * @param  bool $frontend True = frontend, false = backend
     * @return UserActivity
     */
    public function getByInterface($frontend)
    {
        return UserActivity::whereFrontend(true)->get();
    }

    /**
     * Receives all activies performed by a given user
     * 
     * @param  int $userId The ID of the related user 
     * @return UserActivity
     */
    public function getByUser($userId)
    {
        return UserActivity::whereUserId($userId)->get();
    }

    /**
     * Receives all activies related to a given model class
     * 
     * @param  string $modelClass Name of the model class (with namespace!)
     * @return UserActivity
     */    
    public function getByModelClass($modelClass)
    {
        return UserActivity::whereModelClass($modelClass)->get();
    }

    /**
     * Deletes old user activities so that the database table can't
     * get fat.
     *
     * @param  int Delete activities older than x weeks (1 at least)
     * @return void
     */
    public function deleteOld($weeks = 1)
    {
        $weeks = (int) $weeks;

        if ($weeks < 1) {
            throw new Exception('UserActivities: $weeks is smaller than 1!');
        }

        UserActivity::where('created_at', '<', DB::raw('NOW() - INTERVAL '.$weeks.' WEEK'))->delete();
    }

    /**
     * Deletes all user activities.
     *
     * @return void
     */
    public function deleteAll()
    {
        UserActivity::truncate();
    }

}

