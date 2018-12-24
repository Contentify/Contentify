<?php

namespace App\Modules\Forums;

use BaseModel;
use BBCode;
use Cache;
use DB;
use Illuminate\Database\Eloquent\Builder;
use SoftDeletingTrait;
use User;

/**
 * @property \Carbon                         $created_at
 * @property \Carbon                         $deleted_at
 * @property string                          $text
 * @property int                             $thread_id
 * @property int                             $level
 * @property bool                            $root
 * @property int                             $creator_id
 * @property int                             $updater_id
 * @property \User                           $creator
 * @property \User                           $updater
 * @property \App\Modules\Forums\ForumThread $thread
 */
class ForumPost extends BaseModel
{

    use SoftDeletingTrait;

    /**
     * Pagination: Show how many posts per page?
     */
    const PER_PAGE = 20;

    /**
     * Name of the cache key
     */
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

        // Delete all related forum post reports
        self::deleted(function (self $forumPost)
        {
            DB::table('forum_reports')->wherePostId($forumPost->id)->delete();
        });

        self::saved(function (self $forumPost)
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
                $text = substr($text, 0, $max).'…';
            }
        }

        return $text;
    }

    /**
     * Select only those forums the user has access to.
     * WARNING: Creates JOINs with the forum_threads and the forums tables.
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

            $teamIds = DB::table('team_user')->whereUserId($user->id)->pluck('team_id')->toArray();
            $teamIds[] = -1; // Add -1 as team ID so the SQL statements (`team_id` in (...)) always has valid syntax

            return $query->where('internal', '<=', $internal)->where(function(Builder $query) use ($teamIds)
            {
                $query->whereNull('team_id')
                      ->orWhereIn('team_id', $teamIds);
            });
        } else {
            return $query->whereInternal(0)->whereNull('team_id');
        }
    }

    /**
     * Returns a URL that links to the thread of the current post and focuses the current post
     * 
     * @return string
     */
    public function paginatedPostUrl()
    {
        // Counts the posts before the current post
        $count = DB::table('forum_posts')->whereThreadId($this->thread_id)->whereNull('deleted_at')
            ->where('created_at', '<', $this->created_at)->count();

        $extension = '';
        if ($count >= self::PER_PAGE) {
            $page       = floor($count / self::PER_PAGE + 1);
            $extension  = '?page='.$page;
        }
        $extension .= '#forum-post-id-'.$this->id;

        $url = url('forums/threads/'.$this->thread->id.'/'.$this->thread->slug.$extension);

        return $url;
    }

}
