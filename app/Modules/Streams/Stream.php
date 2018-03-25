<?php 

namespace App\Modules\Streams;

use SoftDeletingTrait, Comment, BaseModel;

/**
 * @property int $id
 * @property \Carbon $deleted_at
 * @property string $title
 * @property string $slug
 * @property string $url
 * @property string $permanent_id
 * @property string $provider
 * @property string $thumbnail
 * @property bool $online
 * @property int $viewers
 * @property \User $creator
 */
class Stream extends BaseModel 
{

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title', 'url', 'permanent_id', 'provider', 'thumbnail'];

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
    public static $providers = ['twitch' => 'Twitch', 'hitbox' => 'Hitbox (Deprecated)', 'smashcast' => 'Smashcast'];

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