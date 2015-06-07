<?php namespace App\Modules\Forums\Http\Controllers;

use App\Modules\Forums\Models\Forum;
use App\Modules\Forums\Models\ForumThread;
use App\Modules\Forums\Models\ForumPost;
use DB, Input, View, Redirect, URL, FrontController;

class ThreadsController extends FrontController {

    /**
     * Shows a forum thread
     *
     * @param  int  $id The ID of the thread
     * @return void
     */
    public function show($id)
    {
        $forumThread = ForumThread::isAccessible()->findOrFail($id);

        $forumPosts = ForumPost::whereThreadId($forumThread->id)->orderBy('created_at', 'asc')->paginate(20);

        $this->pageView('forums::show_thread', compact('forumThread', 'forumPosts'));
    }

    /**
     * Shows all forum threads with new posts
     *
     * @return void
     */
    public function showNew()
    {
        $forumThreads = ForumThread::isAccessible()->where('forum_threads.updated_at', '>', user()->last_login)->get();

        $this->pageView('forums::show_new_thread', compact('forumThreads'));
    }

    /**
     * Creates a thread (= create a root post)
     *
     * @param int  $forumId The ID of the forum
     */
    public function create($forumId)
    {
        $this->pageView('forums::root_post_form', compact('forumId'));
    }

    /**
     * Stores a thread
     *
     * @param int $forumId The ID of the forum
     */
    public function store($forumId)
    {
        $forum = Forum::isAccessible()->findOrFail($forumId);

        $forumPost = new ForumPost(Input::all());
        $forumPost->root = true;
        $forumPost->creator_id = user()->id;

        $forumThread = new ForumThread(Input::all());
        $forumThread->forum_id = $forumId;
        $forumThread->creator_id = user()->id;
        $forumThread->createSlug();

        if (! $forumPost->isValid()) {
            return Redirect::to('forums/threads/create')
                ->withInput()->withErrors($forumPost->getErrors());
        }

        if (! $forumThread->isValid()) {
            return Redirect::to('forums/threads/create')
                ->withInput()->withErrors($forumThread->getErrors());
        }

        $forumThread->forceSave();
        $forumPost->thread_id = $forumThread->id;
        $forumPost->forceSave();
        
        $forum->latest_thread_id = $forumThread->id;
        $forum->threads_count++;
        $forum->posts_count++;
        $forum->forceSave();

        $user = user();
        $user->posts_count++;
        $user->save();

        $this->alertFlash(trans('app.created', ['Thread']));
        return Redirect::to('forums/threads/'.$forumThread->id.'/'.$forumThread->slug);
    }

    /**
     * Edits a thread
     * 
     * @param int The id of the thread
     */
    public function edit($id)
    {
        $forumThread = ForumThread::isAccessible()->findOrFail($id);
        $forumPost = ForumPost::whereThreadId($forumThread->id)->firstOrFail();

        if (! ($this->hasAccessUpdate() or $forumPost->creator_id == user()->id)) {
            return $this->alertError(trans('app.access_denied'));
        }

        $this->pageView('forums::root_post_form', compact('forumThread', 'forumPost'));
    }

    /**
     * Updates a thread
     * 
     * @param int The id of the thread
     */
    public function update($id)
    {
        $forumThread = ForumThread::isAccessible()->findOrFail($id);
        $forumPost = ForumPost::whereThreadId($forumThread->id)->firstOrFail();

        if (! ($this->hasAccessUpdate() or $forumPost->creator_id == user()->id)) {
            return $this->alertError(trans('app.access_denied'));
        }

        $forumPost->fill(Input::all());
        $forumPost->updater_id = user()->id;
        $forumThread->fill(Input::all());
        $forumThread->updater_id = user()->id;
        $forumThread->createSlug();

        if (! $forumPost->isValid()) {
            return Redirect::to('forums/threads/create')
                ->withInput()->withErrors($forumPost->getErrors());
        }

        if (! $forumThread->isValid()) {
            return Redirect::to('forums/threads/create')
                ->withInput()->withErrors($forumThread->getErrors());
        }

        $forumPost->forceSave();
        $forumThread->forceSave();

        $this->alertFlash(trans('app.updated', ['Thread']));
        return Redirect::to('forums/threads/'.$forumThread->id.'/'.$forumThread->slug);     
    }

    /**
     * Deletes a thread
     * 
     * @param int The id of the thread
     */
    public function delete($id)
    {
        $forumPost = ForumPost::isAccessible()->findOrFail($id);   

        $forumThread = ForumThread::findOrFail($id);
        $forum = $forumThread->forum;

        ForumPost::whereThreadId($forumThread->id)->delete();

        /*
         * Updates the users posts counter
         */
        $query = DB::table('forum_posts')
            ->whereThreadId(DB::raw($forumThread->id))
            ->groupBy('creator_id')
            ->select('creator_id', DB::raw('COUNT(creator_id) AS count'))
            ->toSql();

        DB::table('users')
            ->join(DB::raw('('.$query.') AS sq'), 'id', '=', 'creator_id')
            ->update(['posts_count' => DB::raw('posts_count - count')]);

        $forumThread->delete();

        $forum->refresh();
    }

    /**
     * Makes a thread sticky or unsticky
     *
     * @param int $id The ID of the thread
     */
    public function sticky($id)
    {
        if (! ($this->hasAccessUpdate() or $forumPost->creator_id == user()->id)) {
            return $this->alertError(trans('app.access_denied'));
        }

        $forumThread = ForumThread::isAccessible()->findOrFail($id);

        $forumThread->sticky = 1 - $forumThread->sticky;
        $forumThread->forceSave();

        $this->alertFlash(trans('app.updated', ['Thread']));
        return Redirect::to('forums/'.$forumThread->forum->id);
    }

    /**
     * Closes a thread or reopens it
     *
     * @param int $id The ID of the thread
     */
    public function closed($id)
    {
        if (! ($this->hasAccessUpdate() or $forumPost->creator_id == user()->id)) {
            return $this->alertError(trans('app.access_denied'));
        }

        $forumThread = ForumThread::isAccessible()->findOrFail($id);

        $forumThread->closed = 1 - $forumThread->closed;
        $forumThread->forceSave();

        $this->alertFlash(trans('app.updated', ['Thread']));
        return Redirect::to('forums/'.$forumThread->forum->id);
    }

    /**
     * Shows a form where the user can choose another forum
     *
     * @param int $id The ID of the thread
     */
    public function getMove($id)
    {
        if (! ($this->hasAccessUpdate() or $forumPost->creator_id == user()->id)) {
            return $this->alertError(trans('app.access_denied'));
        }

        $model = ForumThread::isAccessible()->findOrFail($id);
        $modelClass = get_class($model);

        $forums = Forum::isRoot(false)->get(); 

        return $this->pageView('forums::move_thread', compact('model', 'modelClass', 'forums'));
    }

    /**
     * Moves a thread
     *
     * @param int  $id The ID of the thread
     */
    public function postMove($id)
    {
        if (! ($this->hasAccessUpdate() or $forumPost->creator_id == user()->id)) {
            return $this->alertError(trans('app.access_denied'));
        }
        
        $forumThread = ForumThread::isAccessible()->findOrFail($id);

        $oldForum = $forumThread->forum;

        $forumThread->fill(Input::all());
        $forumThread->save(); // save() not forceSave() so it checks if the parent forum is valid

        $newForum = Forum::whereId($forumThread->forum_id)->firstOrFail(); // We cant simply access $forumThread->forum!
        $newForum->refresh();
        $oldForum->refresh();

        $this->alertFlash(trans('app.updated', ['Thread']));
        return Redirect::to('forums/'.$forumThread->forum_id);
    }

    public function globalSearch($subject)
    {
        $forumThreads = ForumThread::isAccessible()->where('forum_threads.title', 'LIKE', '%'.$subject.'%')->get();

        $results = array();
        foreach ($forumThreads as $forumThread) {
            $results[$forumThread->title] = URL::to('forums/threads/'.$forumThread->id.'/'.$forumThread->slug);
        }

        return $results;
    }

}