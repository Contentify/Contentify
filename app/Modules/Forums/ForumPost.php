<?php namespace App\Modules\Forums;

use DB, BBCode, Cache, SoftDeletingTrait, BaseModel;

class ForumPost extends BaseModel {

    use SoftDeletingTrait;

    const CACHE_KEY = 'forums::posts.';

    protected $dates = ['deleted_at'];

    protected $fillable = ['text', 'thread_id'];

    protected $rules = [
        'text'      => 'required|min:3',
        'thread_id' => 'integer'
    ];

    public static $relationsData = [
        'creator'       => [self::BELONGS_TO, 'User', 'title' => 'username'],
        'updater'       => [self::BELONGS_TO, 'User', 'title' => 'username'],
        'thread'        => [self::BELONGS_TO, 'App\Modules\Forums\ForumThread'],
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
        $rendered = emojis($rendered);
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

    /**
     * Return just the plain forum post's text (WITHOUT BBCode).
     * (Similar to render BBCode without the tags but it uses caching.)
     *
     * @param int $max Limits the number of characters. 0/null = no limit
     * @return string
     */
    public function plainText($max = null)
    {
        $text = strip_tags($this->renderText());

        if ($max) {
            if (strlen($text) > $max) {
                $text = substr($text, 0, $max).'...';
            }
        }

        return $text;
    }

    /**
     * Select only those forums the user has access to.
     * WARNING: Creates JOINs with the forum_threads and the forums table.
     *
     * @param Builder   $query  The Eloquent Builder object
     * @param User      $user   User model or null if it's the current client
     * @return Builder
     */
    public function scopeIsAccessible($query, $user = null)
    {
        $query->select('forum_posts.*')
            ->join('forum_threads', 'forum_posts.thread_id', '=', 'forum_threads.id')
            ->join('forums', 'forum_threads.forum_id', '=', 'forums.id');

        if (! $user) {
            $user = user();
        }

        if ($user) {
            $internal = $user->hasAccess('internal');

            $teamIds = DB::table('team_user')->whereUserId($user->id)->lists('team_id');
            $teamIds[] = -1; // Add -1 as team ID so the SQL statements (`team_id` in (...)) always has valid syntax

            return $query->where('internal', '<=', $internal)->where(function($query) use ($teamIds)
            {
                $query->whereNull('team_id')
                      ->orWhereIn('team_id', $teamIds);
            });
        } else {
            return $query->whereInternal(0)->whereNull('team_id');
        }  
    }

}