<?php namespace App\Modules\Videos\Models;

use OpenGraph, SoftDeletingTrait, BaseModel;

class Video extends BaseModel {

    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title', 'url', 'permanent_id', 'provider'];

    protected $slugable = true;

    public static $rules = [
        'title'         => 'required',
        'url'           => 'required',
        'provider'      => 'required',
    ];

    public static $relationsData = [
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

    /**
     * Array with the names and titles of available video providers (platforms).
     * Be aware that if you add a provider you should also take a look at the tempaltes
     * and add JS/HTML handling for the provider.
     * @var array
     */
    public static $providers = ['youtube' => 'YouTube'];

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
     * @return array
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