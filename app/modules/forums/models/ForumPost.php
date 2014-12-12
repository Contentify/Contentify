<?php namespace App\Modules\Forums\Models;

use BBCode, Cache, SoftDeletingTrait, BaseModel;

class ForumPost extends BaseModel {

    use SoftDeletingTrait;

    const CACHE_KEY = 'forums.posts.';

    protected $dates = ['deleted_at'];

    protected $fillable = ['text', 'thread_id'];

    protected $rules = [
        'text'      => 'required|min:3',
    ];

    public static $relationsData = [
        'creator'       => [self::BELONGS_TO, 'User', 'title' => 'username'],
        'updater'       => [self::BELONGS_TO, 'User', 'title' => 'username'],
        'thread'        => [self::BELONGS_TO, 'App\Modules\Forums\Models\ForumThread'],
    ];

    public static function boot()
    {
        parent::boot();

        self::saved(function ($forumPost)
        {
            $forumPost->cache();
        });
    }

    /**
     * Caches this forum post - we don't want to parse BBCodes each time
     * we want to display a forum post.
     * 
     * @return void
     */
    public function cache()
    {
        $bbcode = new BBCode();
        $rendered = $bbcode->render($this->text);
        Cache::put(self::CACHE_KEY.$this->id, $rendered, 60);
    }

    /**
     * Renders the forum post's text (with BBCode converted to HTML code)
     * 
     * @return string
     */
    public function renderText()
    {
        $key = self::CACHE_KEY.$this->id;

        if (! Cache::has($key)) {
            $this->cache();
        }

        return Cache::get($key);
    }

}