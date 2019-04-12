<?php

namespace Contentify;

use Contentify\Models\UserActivity;
use DB;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;

/**
 * This class is the user activities manager. This is a logger for user activities, especially for administrators.
 * The class offers methods to create new log entries, retrive log entries and delete log entries.
 */
class UserActivities
{

    /*
     * NOTE: User activities are not the same as permissions!
     * It's possible to create activity constants that are not equal to a permission constant.
     * Nevertheless it makes sense to mirror the permission constants here.
     */
    const ACTIVITY_CREATE = PERM_CREATE;

    const ACTIVITY_READ = PERM_READ;

    const ACTIVITY_UPDATE = PERM_UPDATE;

    const ACTIVITY_DELETE = PERM_DELETE;

    /**
     * Add a new activity
     *
     * @param int         $activityId The ID of the activity - use one of the provided constants!
     * @param bool        $frontend   The surface  - true = frontend, false = backend
     * @param int         $userId     The ID of the related user (usually user()->id)
     * @param string|null $modelClass The name of the model class (with namespace!)
     * @param string|null $info       Additional information
     * @param int|null    $createdAt  Date and time when the activity was created. Null = now.
     * @return void
     * @throws InvalidArgumentException
     */
    public function add(
        int $activityId,
        bool $frontend,
        int $userId,
        string $modelClass = null,
        string $info = null,
        int $createdAt = null
    )
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
            throw new InvalidArgumentException('UserActivities: Cannot create UserActivity model - validation failed.');
        }
    }

    /**
     * Add an user activity of type "create"
     *
     * @param bool        $frontend
     * @param int         $userId
     * @param string|null $modelClass
     * @param string|null $info
     * @param int|null    $createdAt
     */
    public function addCreate(
        bool $frontend,
        int $userId,
        string $modelClass = null,
        string $info = null,
        int $createdAt = null
    )
    {
        $this->add(self::ACTIVITY_CREATE, $frontend, $userId, $modelClass, $info, $createdAt);
    }

    /**
     * Add an user activity of type "read"
     *
     * @param bool        $frontend
     * @param int         $userId
     * @param string|null $modelClass
     * @param string|null $info
     * @param int|null    $createdAt
     */
    public function addRead(
        bool $frontend,
        int $userId,
        string $modelClass = null,
        string $info = null,
        int $createdAt = null
    )
    {
        $this->add(self::ACTIVITY_READ, $frontend, $userId, $modelClass, $info, $createdAt);
    }

    /**
     * Add an user activity of type "update"
     *
     * @param bool        $frontend
     * @param int         $userId
     * @param string|null $modelClass
     * @param string|null $info
     * @param int|null    $createdAt
     */
    public function addUpdate(
        bool $frontend,
        int $userId,
        string $modelClass = null,
        string $info = null,
        int $createdAt = null
    )
    {
        $this->add(self::ACTIVITY_UPDATE, $frontend, $userId, $modelClass, $info, $createdAt);
    }

    /**
     * Add an user activity of type "delete"
     *
     * @param bool        $frontend
     * @param int         $userId
     * @param string|null $modelClass
     * @param string|null $info
     * @param int|null    $createdAt
     */
    public function addDelete(
        bool $frontend,
        int $userId,
        string $modelClass = null,
        string $info = null,
        int $createdAt = null
    )
    {
        $this->add(self::ACTIVITY_DELETE, $frontend, $userId, $modelClass, $info, $createdAt);
    }

    /**
     * Receives all activities with a given activity ID
     * 
     * @param int $activityId The ID of the activity - use one of the provided constants!
     * @return UserActivity[]|Collection
     */
    public function getByActivity(int $activityId) : Collection
    {
        return UserActivity::whereActivityId($activityId)->get();
    }

    /**
     * Receives all activities with a given interface (frontend / backend)
     * 
     * @param bool $frontend True = frontend, false = backend
     * @return UserActivity[]|Collection
     */
    public function getByInterface(bool $frontend) : Collection
    {
        return UserActivity::whereFrontend($frontend)->get();
    }

    /**
     * Receives all activities performed by a given user
     * 
     * @param int $userId The ID of the related user
     * @return UserActivity[]|Collection
     */
    public function getByUser(int $userId) : Collection
    {
        return UserActivity::whereUserId($userId)->get();
    }

    /**
     * Receives all activities related to a given model class
     * 
     * @param string $modelClass Name of the model class (with namespace!)
     * @return UserActivity[]|Collection
     */    
    public function getByModelClass(string $modelClass) : Collection
    {
        return UserActivity::whereModelClass($modelClass)->get();
    }

    /**
     * Deletes old user activities so that the database table can't get fat.
     *
     * @param int $weeks Delete activities older than x weeks (1 at least)
     * @return void
     * @throws InvalidArgumentException|\Exception
     */
    public function deleteOld(int $weeks = 1)
    {
        $weeks = (int) $weeks;

        if ($weeks < 1) {
            throw new InvalidArgumentException('UserActivities: $weeks is smaller than 1!');
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

