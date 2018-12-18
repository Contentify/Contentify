<?php

namespace App\Modules\Questions;

use BaseModel;
use SoftDeletingTrait;

/**
 * @property \Carbon                            $created_at
 * @property \Carbon                            $deleted_at
 * @property string                             $title
 * @property string                             $answer
 * @property bool                               $published
 * @property int                                $question_cat_id
 * @property int                                $creator_id
 * @property int                                $updater_id
 * @property \App\Modules\Questions\QuestionCat $questionCat
 * @property \User                              $creator
 */
class Question extends BaseModel
{

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title', 'answer', 'position', 'published', 'question_cat_id'];

    protected $rules = [
        'title'     => 'required|min:3',
        'answer'    => 'required|min:3',
        'position'  => 'integer',
        'published' => 'boolean',
    ];

    public static $relationsData = [
        'questionCat' => [self::BELONGS_TO, 'App\Modules\Questions\QuestionCat'],
        'creator'     => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

}
