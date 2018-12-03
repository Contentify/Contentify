<?php

namespace App\Modules\Forums\Http\Controllers;

use App\Modules\Forums\Forum;
use App\Modules\Forums\ForumPost;
use App\Modules\Forums\ForumThread;
use Contentify\GlobalSearchInterface;
use DB;
use FrontController;
use Input;
use Redirect;
use URL;

class ThreadsController extends FrontController implements GlobalSearchInterface
{

    /**
     * Shows a forum thread
     *
     * @param  int  $id The ID of the thread
     * @return void
     */
    public function show($id)
    {
        $forumThread = ForumThread::isAccessible()->findOrFail($id);

        $forumPosts = ForumPost::whereThreadId($forumThread->id)->orderBy('created_at', 'asc')
            ->paginate(ForumPost::PER_PAGE);

        $this->title($forumThread->title);

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

        $this->pageView('forums::show_new_threads', compact('forumThreads'));
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($forumId)
    {
        /** @var Forum $forum */
        $forum = Forum::isAccessible()->findOrFail($forumId);

        $forumPost = new ForumPost(Input::all());
        $forumPost->root = true;
        $forumPost->creator_id = user()->id;
        $forumPost->updater_id = null;

        $forumThread = new ForumThread(Input::all());
        $forumThread->forum_id = $forumId;
        $forumThread->creator_id = user()->id;
        $forumThread->updater_id = null;
        $forumThread->createSlug();

        if (! $forumPost->isValid()) {
            return Redirect::to('forums/threads/create/'.$forum->id)
                ->withInput()->withErrors($forumPost->getErrors());
        }

        if (! $forumThread->isValid()) {
            return Redirect::to('forums/threads/create/'.$forum->id)
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

        $this->alertFlash(trans('app.created', [trans('app.object_thread')]));
        return Redirect::to('forums/threads/'.$forumThread->id.'/'.$forumThread->slug);
    }

    /**
     * Edits a thread
     * 
     * @param int $id The ID of the thread
     * @return void
     */
    public function edit($id)
    {
        /** @var ForumThread $forumThread */
        $forumThread = ForumThread::isAccessible()->findOrFail($id);
        /** @var ForumPost $forumPost */
        $forumPost = ForumPost::whereThreadId($forumThread->id)->firstOrFail();

        if (! ($this->hasAccessUpdate() or $forumPost->creator_id == user()->id)) {
            $this->alertError(trans('app.access_denied'));
            return;
        }

        $this->pageView('forums::root_post_form', compact('forumThread', 'forumPost'));
    }

    /**
     * Updates a thread
     *
     * @param int $id The ID of the thread
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function update($id)
    {
        /** @var ForumThread $forumThread */
        $forumThread = ForumThread::isAccessible()->findOrFail($id);
        /** @var ForumPost $forumPost */
        $forumPost = ForumPost::whereThreadId($forumThread->id)->firstOrFail();

        if (! ($this->hasAccessUpdate() or $forumPost->creator_id == user()->id)) {
            $this->alertError(trans('app.access_denied'));
            return null;
        }

        $forumPost->fill(Input::all());
        $forumPost->updater_id = user()->id;
        $forumThread->fill(Input::all());
        $forumThread->updater_id = user()->id;
        $forumThread->createSlug();

        if (! $forumPost->isValid()) {
            return Redirect::to('forums/threads/edit/'.$forumThread->forum_id)
                ->withInput()->withErrors($forumPost->getErrors());
        }

        if (! $forumThread->isValid()) {
            return Redirect::to('forums/threads/edit/'.$forumThread->forum_id)
                ->withInput()->withErrors($forumThread->getErrors());
        }

        $forumPost->forceSave();
        $forumThread->forceSave();

        $this->alertFlash(trans('app.updated',  [trans('app.object_thread')]));
        return Redirect::to('forums/threads/'.$forumThread->id.'/'.$forumThread->slug);
    }

    /**
     * Deletes a thread
     *
     * @param int $id The ID of the thread
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        if (! $this->checkAccessDelete()) {
            return null;
        }

        /** @var ForumThread $forumThread */
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

        $this->alertFlash(trans('app.deleted', [trans('app.object_thread')]));
        return Redirect::to('forums/'.$forumThread->forum->id);
    }

    /**
     * Makes a thread sticky or not sticky
     *
     * @param int $id The ID of the thread
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function sticky($id)
    {
        if (! ($this->hasAccessUpdate())) {
            $this->alertError(trans('app.access_denied'));
            return null;
        }

        /** @var ForumThread $forumThread */
        $forumThread = ForumThread::isAccessible()->findOrFail($id);

        $forumThread->sticky = 1 - $forumThread->sticky;
        $forumThread->forceSave();

        $this->alertFlash(trans('app.updated', [trans('app.object_thread')]));
        return Redirect::to('forums/'.$forumThread->forum->id);
    }

    /**
     * Closes a thread or reopens it
     *
     * @param int $id The ID of the thread
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function closed($id)
    {
        if (! ($this->hasAccessUpdate())) {
            $this->alertError(trans('app.access_denied'));
            return null;
        }

        /** @var ForumThread $forumThread */
        $forumThread = ForumThread::isAccessible()->findOrFail($id);

        $forumThread->closed = 1 - $forumThread->closed;
        $forumThread->forceSave();

        $this->alertFlash(trans('app.updated', [trans('app.object_thread')]));
        return Redirect::to('forums/'.$forumThread->forum->id);
    }

    /**
     * Shows a form where the user can choose another forum
     *
     * @param int $id The ID of the thread
     * @return void
     */
    public function getMove($id)
    {
        if (! ($this->hasAccessUpdate())) {
            $this->alertError(trans('app.access_denied'));
            return null;
        }

        $model = ForumThread::isAccessible()->findOrFail($id);
        $modelClass = get_class($model);

        $forums = Forum::isRoot(false)->get(); 

        $this->pageView('forums::move_thread', compact('model', 'modelClass', 'forums'));
    }

    /**
     * Moves a thread
     *
     * @param int $id The ID of the thread
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function postMove($id)
    {
        if (! ($this->hasAccessUpdate())) {
            $this->alertError(trans('app.access_denied'));
            return null;
        }

        /** @var ForumThread $forumThread */
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
    
    /**
     * This method is called by the global search (SearchController->postCreate()).
     * Its purpose is to return an array with result for a specific search query.
     * 
     * @param  string $subject The search term
     * @return string[]
     */
    public function globalSearch($subject)
    {
        /** @var ForumThread[] $forumThreads */
        $forumThreads = ForumThread::isAccessible()->where('forum_threads.title', 'LIKE', '%'.$subject.'%')->get();

        $results = array();
        foreach ($forumThreads as $forumThread) {
            $results[$forumThread->title] = URL::to('forums/threads/'.$forumThread->id.'/'.$forumThread->slug);
        }

        return $results;
    }

}
