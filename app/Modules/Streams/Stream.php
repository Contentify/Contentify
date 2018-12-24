<?php 

namespace App\Modules\Streams;

use App\Modules\Streams\Api\SmashcastApi;
use App\Modules\Streams\Api\TwitchApi;
use BaseModel;
use Comment;
use SoftDeletingTrait;

/**
 * @property \Carbon $created_at
 * @property \Carbon $deleted_at
 * @property string  $title
 * @property string  $slug
 * @property string  $url
 * @property string  $permanent_id
 * @property string  $provider
 * @property string  $thumbnail
 * @property bool    $online
 * @property int     $viewers
 * @property int     $access_counter
 * @property int     $creator_id
 * @property int     $updater_id
 * @property \User   $creator
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
     *
     * @var string[]
     */
    public static $providers = [
        TwitchApi::PROVIDER => 'Twitch',
        SmashcastApi::PROVIDER => 'Smashcast',
    ];

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
