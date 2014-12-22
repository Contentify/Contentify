<?php namespace App\Modules\Streams\Models;

use SoftDeletingTrait, BaseModel;

class Stream extends BaseModel {

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title', 'url', 'permanent_id', 'provider'];

    protected $slugable = true;

    protected $rules = [
        'title'         => 'required|min:3',
        'url'           => 'required|url',
        'provider'      => 'required',
    ];

    public static $relationsData = [
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

    /**
     * Array with the names and titles of available video providers (platforms).
     * Be aware that if you add a provider you should also take a look at the template files
     * and add JS/HTML handling for the provider.
     * @var array
     */
    public static $providers = ['twitch' => 'Twitch'];

    /**
     * Count the comments that are related to this video.
     * 
     * @return int
     */
    public function countComments()
    {
        return Comment::count('streams', $this->id);
    }

}