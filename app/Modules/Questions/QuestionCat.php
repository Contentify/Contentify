<?php

namespace App\Modules\Questions;

use BaseModel;
use SoftDeletingTrait;

/**
 * @property \Carbon                  $created_at
 * @property \Carbon                  $deleted_at
 * @property string                   $title
 * @property string                   $image
 * @property \App\Modules\News\News[] $news
 * @property \User                    $creator
 */
class QuestionCat extends BaseModel
{

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title'];
    
    protected $rules = [
        'title'     => 'required|min:3',
    ];

    public static $relationsData = [
        'questions' => [self::HAS_MANY, 'App\Modules\Questions\Question', 'dependency' => true],
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];
    
}
