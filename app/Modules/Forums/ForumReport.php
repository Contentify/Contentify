<?php

namespace App\Modules\Forums;

use BaseModel;

/**
 * @property \Carbon                       $created_at
 * @property \Carbon                       $deleted_at
 * @property string                        $text
 * @property int                           $post_id
 * @property int                           $creator_id
 * @property int                           $updater_id
 * @property \User                         $creator
 * @property \User                         $updater
 * @property \App\Modules\Forums\ForumPost $post
 */
class ForumReport extends BaseModel
{

    protected $dates = ['deleted_at'];

    protected $fillable = ['text', 'post_id'];

    protected $rules = ['post_id'  => 'integer'];

    public static $relationsData = [
        'creator'       => [self::BELONGS_TO, 'User', 'title' => 'username'],
        'updater'       => [self::BELONGS_TO, 'User', 'title' => 'username'],
        'post'          => [self::BELONGS_TO, 'App\Modules\Forums\ForumPost'],
    ];

}
