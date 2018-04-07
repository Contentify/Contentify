<?php

namespace Contentify\Models;

/**
 * @property int $id
 * @property \Carbon $created_at
 * @property \Carbon $updated_at
 * @property int $activity_id
 * @property bool $frontend
 * @property int $user_id
 * @property string $model_class
 * @property string $info
 * @property \User $user
 */
class UserActivity extends BaseModel
{

    protected $fillable = [
        'activity_id', 
        'frontend', 
        'user_id',
        'model_class',
        'info',
        'created_at',
    ];

    protected $rules = [
        'activity_id'   => 'required|integer',
        'frontend'      => 'required|boolean',
        'user_id'       => 'required|integer|min:1',
        'created_at'    => 'required',
    ];

    public static $relationsData = [
        'user'       => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

}