<?php namespace App\Modules\Forums\Models;

use MsgException, SoftDeletingTrait, BaseModel;

class Forum extends BaseModel {

    use SoftDeletingTrait;

    /**
     * Maximum forum nesting level (root forums are on level 0)
     */
    const MAX_LEVEL = 2;

    protected $slugable = true;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title', 'description', 'position', 'internal', 'forum_id'];
    
    protected $rules = [
        'title'     => 'required',
        'position'  => 'integer',
        'internal'  => 'boolean',
        'forum_id'  => 'sometimes|integer',
    ];

    public static $relationsData = [
        'creator'       => [self::BELONGS_TO, 'User', 'title' => 'username'],
        'forum'         => [self::BELONGS_TO, 'App\Modules\Forums\Models\Forum'], // Parent forum
        'latestThread'  => [self::BELONGS_TO, 'App\Modules\Forums\Models\ForumThread'],
        'threads'       => [self::HAS_MANY, 'App\Modules\Forums\Models\ForumThread'],
        'team'          => [self::BELONGS_TO, 'App\Modules\Teams\Models\Team'],
        'forums'        => [self::HAS_MANY, 'App\Modules\Forums\Models\Forum'], // Sub forums
    ];

    /**
     * Select only forums that are root forums (with level = 0)
     *
     * @param Builder   $query  The Eloquent Builder object
     * @param bool      $isRoot If set to false the method behvaes like "isNotRoot()"
     */
    public function scopeIsRoot($query, $isRoot = true)
    {
        if ($isRoot) {
            return $query->whereLevel(0);    
        } else {
            return $query->where('level', '>', 0);
        }
    }

    public static function boot()
    {
        parent::boot();

        self::saving(function($forum)
        {
            $forums         = [$forum->id];
            $forum->level   = 0;

            /*
             * Circle- (a forum cannot be its own child) and level-checker
             */
            $foreignForum = $forum->forum;
            while ($foreignForum) {
                $forum->level++;

                if ($forum->level > self::MAX_LEVEL) {
                    throw new MsgException(trans('forums::max_level'));
                }

                if (in_array($foreignForum->id, $forums)) {
                    throw new MsgException(trans('forums::forums_circle'));
                } else {
                    $forums[] = $foreignForum->id;
                    $foreignForum = $foreignForum->forum;
                }
            }
        });
    }

    /**
     * Refreshes the forum's meta infos
     *
     * @return void
     */
    public function refresh()
    {
        if (! $this->forum_id) {
            return; // Root forums (level = 0) do not need refreshes
        }

        $forumThread = ForumThread::whereForumId($this->id)->orderBy('created_at', 'desc')->first();
        $threadsCount = ForumThread::whereForumId($this->id)->count();

        $childThreadsCount = 0;
        foreach ($this->forums as $forum) {
            $childThreadsCount += $forum->threads_count;
        }

        $this->latest_thread_id = null;
        if ($forumThread) {
            $this->latest_thread_id = $forumThread->id;
        }
        $this->threads_count = $threadsCount + $childThreadsCount;
        $this->forceSave();

        /*
         * Every forum has to call the refresh method of its parent forum,
         * because their meta infos all depend on their child forums.
         * Therefore we do a cascading method call.
         */
        if ($this->forum_id) {
            $this->forum->refresh();
        }
    }

}