<?php 

namespace App\Modules\Videos;

use Comment, OpenGraph, SoftDeletingTrait, BaseModel;

class Video extends BaseModel {

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