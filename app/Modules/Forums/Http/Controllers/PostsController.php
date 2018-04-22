<?php

namespace App\Modules\Forums\Http\Controllers;

use App\Modules\Forums\ForumPost;
use App\Modules\Forums\ForumReport;
use App\Modules\Forums\ForumThread;
use FrontController;
use Input;
use Redirect;
use Response;
use User;

class PostsController extends FrontController
{

    /**
     * Show a single post
     *
     * @param int $id The id of the post
     */
    public function show($id)
    {
        $forumPost = ForumPost::isAccessible()->findOrFail($id);

        $this->pageView('forums::show_single_post', compact('forumPost'));
    }

    /**
     * Returns a forum post as JSON (as response to an AJAX call)
     * 
     * @param  int $id The ID of the post
     * @return mixed
     */
    public function get($id)
    {
        $forumPost = ForumPost::isAccessible()->findOrFail($id);

        return Response::json($forumPost);
    }

    /**
     * Stores a post
     *
     * @param int $id The ID of the thread
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function store($id)
    {
        $forumPost = new ForumPost(Input::all());
        $forumPost->creator_id = user()->id;
        $forumPost->thread_id = $id;

        /** @var ForumThread $forumThread */
        $forumThread = ForumThread::isAccessible()->findOrFail($id);

        if ($forumThread->closed) {
            $this->alertError(trans('forums::closed_info'));
            return null;
        }

        $valid = $forumPost->save();

        if (! $valid) {
            return Redirect::to(
                'forums/threads/'.$id.'/'.$forumThread->slug.'?page='.$forumThread->countPages().'#create-forum-post'
            )->withInput()->withErrors($forumPost->getErrors());
        }

        $forumThread->posts_count++;
        $forumThread->forceSave();

        $user = user();
        $user->posts_count++;
        $user->save();

        $this->alertFlash(trans('app.created', ['Post']));

        return Redirect::to($forumPost->paginatedPostUrl());
    }

    /**
     * Edits a post
     *
     * @param int $id The ID of the post
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function edit($id)
    {
        /** @var ForumPost $forumPost */
        $forumPost = ForumPost::isAccessible()->findOrFail($id);

        if (! ($this->hasAccessUpdate() or $forumPost->creator_id == user()->id)) {
            $this->alertError(trans('app.access_denied'));
            return null;
        }

        /*
         * If the post is a root post, edit the thread instead of the post.
         */
        if ($forumPost->root) {
            return Redirect::to('forums/threads/edit/'.$forumPost->thread_id);
        }

        $this->pageView('forums::post_form', compact('forumPost'));
    }

    /**
     * Updates a post
     *
     * @param int $id The ID of the post
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function update($id)
    {
        /** @var ForumPost $forumPost */
        $forumPost = ForumPost::isAccessible()->findOrFail($id);

        if (! ($this->hasAccessUpdate() or $forumPost->creator_id == user()->id)) {
            $this->alertError(trans('app.access_denied'));
            return null;
        }

        /*
         * If the post is a root post, delete the thread instead of the post.
         */
        if ($forumPost->root) {
            return Redirect::action('forums/thread/update/'.$forumPost->thread_id);
        }

        $forumPost->fill(Input::all());
        $forumPost->updater_id = user()->id;
        $valid = $forumPost->save();
        
        if (! $valid) {
            return Redirect::to('forums/posts/edit/'.$forumPost->id)
                ->withInput()->withErrors($forumPost->getErrors());
        }

        $this->alertFlash(trans('app.updated', ['Post']));

        return Redirect::to($forumPost->paginatedPostUrl());
    }

    /**
     * Deletes a post
     *
     * @param int $id The ID of the post
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function delete($id)
    {
        if (! $this->checkAccessDelete()) {
            return null;
        }

        /** @var ForumPost $forumPost */
        $forumPost = ForumPost::isAccessible()->findOrFail($id);

        /*
         * If the post is a root post, delete the thread instead of the post.
         */
        if ($forumPost->root) {
            return Redirect::action('forums/threads/delete/'.$forumPost->thread_id);
        }

        $user = user();
        $user->posts_count--;
        $user->save();

        $thread = $forumPost->thread;

        $forumPost->delete();

        $thread->refresh();

        $this->alertFlash(trans('app.deleted', ['Post']));
        return Redirect::to('forums/threads/'.$thread->id.'/'.$thread->slug);
    }

    /**
     * Reports a post
     *
     * @param int $id The ID of the post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function report($id)
    {
        /** @var ForumPost $forumPost */
        $forumPost = ForumPost::isAccessible()->findOrFail($id); 

        $forumReport = ForumReport::whereCreatorId(user()->id)->wherePostId($id)->first();

        if ($forumReport) {
            $this->alertFlash(trans('forums::already_reported'));
        } else {
            $forumReport = new ForumReport(['post_id' => $id]);
            $forumReport->creator_id = user()->id;
            $forumReport->save();

            $this->alertFlash(trans('forums::reported'));
        }

        return Redirect::to('forums/threads/'.$forumPost->thread->id.'/'.$forumPost->thread->slug);
    }

    /**
     * Shows the latest posts of a user
     * 
     * @param int $userId The ID of the user
     */
    public function showUserPosts($userId)
    {
        $user = User::findOrFail($userId);

        $forumPosts = ForumPost::where('forum_posts.creator_id', '=', $userId)->isAccessible($user)
            ->orderBy('updated_at', 'DESC')->paginate(10); 

        $this->pageView('forums::show_user_posts', compact('forumPosts'));
    }

}