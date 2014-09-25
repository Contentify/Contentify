<?php namespace App\Modules\Matches\Models;

use SoftDeletingTrait, BaseModel;

class Match extends BaseModel {

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = [];

    public static $rules = [
    ];

    public static $relationsData = [
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

    /**
     * Count the comments that are related to this match.
     * 
     * @return int
     */
    public function countComments()
    {
        return Comment::count('match', $this->id);
    }


}