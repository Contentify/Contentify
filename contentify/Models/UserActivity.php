<?php

namespace Contentify\Models;

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