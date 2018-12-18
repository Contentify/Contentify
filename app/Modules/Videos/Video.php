<?php 

namespace App\Modules\Videos;

use BaseModel;
use Comment;
use OpenGraph;
use SoftDeletingTrait;

/**
 * @property \Carbon $created_at
 * @property \Carbon $deleted_at
 * @property string  $title
 * @property string  $slug
 * @property string  $url
 * @property string  $permanent_id
 * @property string  $provider
 * @property int     $access_counter
 * @property int     $creator_id
 * @property int     $updater_id
 * @property \User  $creator
 */
class Video extends BaseModel 
{

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
     *
     * @var string[]
     */
    public static $providers = ['youtube' => 'YouTube', 'vimeo' => 'Vimeo'];

    /**
     * Count the comments that are related to this video.
     * 
     * @return int
     */
    public function countComments()
    {
        return Comment::count('videos', $this->id);
    }

    /**
     * Create an instance of OpenGraph that represents Open Graph tags.
     * 
     * @return OpenGraph
     */
    public function openGraph()
    {
        $og = new OpenGraph(true);

        $og->title($this->title)
            ->type('video.other')
            ->url();

        return $og;
    }

}
