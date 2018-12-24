<?php

namespace App\Modules\Forums;

use BaseModel;
use DB;
use Illuminate\Database\Eloquent\Builder;
use MsgException;
use SoftDeletingTrait;
use User;

/**
 * @property \Carbon                           $created_at
 * @property \Carbon                           $deleted_at
 * @property string                            $title
 * @property string                            $slug
 * @property string                            $description
 * @property int                               $position
 * @property bool                              $internal
 * @property int                               $team_id
 * @property int                               $forum_id
 * @property int                               $level
 * @property int                               $threads_count
 * @property int                               $posts_count
 * @property int|null                          $latest_thread_id
 * @property int                               $creator_id
 * @property int                               $updater_id
 * @property \User                             $creator
 * @property \App\Modules\Forums\Forum         $forum
 * @property \App\Modules\Forums\ForumThread   $latestThread
 * @property \App\Modules\Forums\ForumThread[] $threads
 * @property \App\Modules\Teams\Team           $team
 * @property \App\Modules\Forums\Forum[]       $forums
 */
class Forum extends BaseModel
{

    use SoftDeletingTrait;

    /**
     * Maximum forum nesting level (root forums are on level 0)
     */
    const MAX_LEVEL = 2;

    protected $slugable = true;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title', 'description', 'position', 'internal', 'team_id', 'forum_id'];
    
    protected $rules = [
        'title'     => 'required',
        'position'  => 'integer',
        'internal'  => 'boolean',
        'team_id'   => 'nullable|integer',
        'forum_id'  => 'nullable|integer',
    ];

    public static $relationsData = [
        'creator'       => [self::BELONGS_TO, 'User', 'title' => 'username'],
        'forum'         => [self::BELONGS_TO, 'App\Modules\Forums\Forum'], // Parent forum
        'latestThread'  => [self::BELONGS_TO, 'App\Modules\Forums\ForumThread'],
        'threads'       => [self::HAS_MANY, 'App\Modules\Forums\ForumThread', 'dependency' => true],
        'team'          => [self::BELONGS_TO, 'App\Modules\Teams\Team'],
        'forums'        => [self::HAS_MANY, 'App\Modules\Forums\Forum', 'dependency' => true], // Sub forums
    ];

    public static function boot()
    {
        parent::boot();

        self::saving(function(self $forum)
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

            /*
             * If the forum is not a root forum, copy access rules of
             * its parent forum.
             */
            if ($forum->level > 0) {
                $forum->internal = $forum->forum->internal;
                $forum->team_id  = $forum->forum->team_id;
            }
        });

        self::saved(function(self $forum)
        {
            foreach ($forum->forums as $subForum) {
                $subForum->refreshChildrenAccessRules();
            }
        });
    }

    /**
     * Attribute mutator
     * 
     * @param mixed $value The value
     */
    public function setForumIdAttribute($value)
    {
        $this->attributes['forum_id'] = $value ? $value : null;
    }

    /**
     * Refreshes the access rules (internal, related to a team) of all children
     * of a forum - and of their children and so on.
     * (Note: Sub forums have the same access rules as their parent forums.)
     * 
     * @return void
     */
    public function refreshChildrenAccessRules()
    {
        /*
         * If the forum is not a root forum, copy access rules of
         * its parent forum.
         */
        $this->internal    = $this->forum->internal;
        $this->team_id     = $this->forum->team_id;
        $this->forceSave();

        /*
         * Recursive call of this method for all child forums
         */
        foreach ($this->forums as $forum) {
            $forum->refreshChildrenAccessRules();
        }
    }

    /**
     * Refreshes the forum's meta info
     *
     * @return void
     */
    public function refresh()
    {
        if (! $this->forum_id) {
            return; // Root forums (level = 0) do not need refreshes
        }

        /** @var ForumThread $forumThread */
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
         * because their meta info all depend on their child forums.
         * Therefore we do a cascading method call.
         */
        if ($this->forum_id) {
            $this->forum->refresh();
        }
    }

    /**
     * Select only forums that are root forums (with level = 0)
     *
     * @param Builder   $query  The Eloquent Builder object
     * @param bool      $isRoot If set to false the method behaves like "isNotRoot()"
     * @return Builder
     */
    public function scopeIsRoot($query, $isRoot = true)
    {
        if ($isRoot) {
            return $query->whereLevel(0);
        } else {
            return $query->where('level', '>', 0);
        }
    }

    /**
     * Select only those forums the user has access to
     *
     * @param Builder   $query  The Eloquent Builder object
     * @param User|null $user   User model or null if it's the current client
     * @return Builder
     */
    public function scopeIsAccessible($query, $user = null)
    {
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

}
