<?php

namespace App\Modules\Forums;

use BaseModel;

class ForumReport extends BaseModel {

    protected $dates = ['deleted_at'];

    protected $fillable = ['text', 'post_id'];

    protected $rules = ['post_id'  => 'integer'];

    public static $relationsData = [
        'creator'       => [self::BELONGS_TO, 'User', 'title' => 'username'],
        'updater'       => [self::BELONGS_TO, 'User', 'title' => 'username'],
        'post'          => [self::BELONGS_TO, 'App\Modules\Forums\ForumPost'],
    ];

}