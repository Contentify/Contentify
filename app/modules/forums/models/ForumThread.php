<?php namespace App\Modules\Forums\Models;

use App\Modules\Forums\Models\ForumPost;
use SoftDeletingTrait, BaseModel;

class ForumThread extends BaseModel {

    use SoftDeletingTrait;

    protected $slugable = true;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title', 'sticky', 'closed', 'forum_id'];

    protected $rules = [
        'title'     => 'required|min:3',
        'sticky'    => 'sometimes|boolean',
        'closed'    => 'sometimes|boolean',
        'forum_id'  => 'integer',
    ];

    public static $relationsData = [
        'creator'   => [self::BELONGS_TO, 'User', 'title' => 'username'],
        'forum'     => [self::BELONGS_TO, 'App\Modules\Forums\Models\Forum'],
        'posts'     => [self::HAS_MANY, 'App\Modules\Forums\Models\ForumPost'],
    ];

    /**
     * Refreshes the thread's meta infos
     * 
     * @return void
     */
    public function refresh()
    {
        $forumPost  = ForumPost::whereThreadId($this->id)->orderBy('created_at', 'desc')->firstOrFail();
        $postsCount = ForumPost::whereThreadId($this->id)->count();

        $this->posts_count  = $postsCount;
        $this->updated_at   = $forumPost->updated_at;
        $this->forceSave();
    }

}