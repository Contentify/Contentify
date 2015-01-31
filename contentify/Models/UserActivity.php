<?php namespace Contentify\Models;

class UserActivity extends BaseModel {

    protected $fillable = [
        'activity', 
        'frontend', 
        'user_id',
        'info',
        'created_at',
    ];

    protected $rules = [
        'activity'      => 'required',
        'frontend'      => 'required|boolean',
        'user_id'       => 'required|integer|min:1',
        'created_at'    => 'required',
    ];

    public static $relationsData = [
        'user'       => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

}