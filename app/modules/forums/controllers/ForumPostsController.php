<?php namespace App\Modules\Forums\Controllers;

use App\Modules\Forums\Models\ForumPost;
use Input, Redirect, FrontController;

class ForumPostsController extends FrontController {

    /**
     * Show a single post
     *
     * @param int The id of the post
     */
    public function show($id)
    {
        $forumPost = ForumPost::findOrFail($id);

        $this->pageView('forums::show_single_post', compact('forumPost'));
    }

    /**
     * Stores a post
     *
     * @param int The id of the thread
     */
    public function store($id)
    {
        $forumPost = new ForumPost(Input::all());
        $forumPost->creator_id = user()->id;
        $forumPost->thread_id = $id;

        $forumThread = $forumPost->thread;

        if ($forumThread->closed) {
            $this->message(trans('forums::closed_info'));
            return;
        }

        $valid = $forumPost->save();

        if (! $valid) {
            return Redirect::to('forums/threads/create')
                ->withInput()->withErrors($forumPost->getErrors()); // TODO: Keep this redirect??
        }  

        $forumThread->posts_count++;
        $forumThread->forceSave();

        $user = user();
        $user->posts_count++;
        $user->save();

        $this->messageFlash(trans('app.created', ['Post']));
        return Redirect::to('forums/threads/'.$forumPost->thread->id.'/'.$forumPost->thread->slug);
    }

    /**
     * Edits a post
     * 
     * @param int The id of the post
     */
    public function edit($id)
    {
        $forumPost = ForumPost::findOrFail($id);

        if (! ($this->hasAccessUpdate() or $forumPost->creator_id == user()->id)) {
            return $this->message(trans('app.access_denied'));
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
     * @param int The id of the post
     */
    public function update($id)
    {
        $forumPost = ForumPost::findOrFail($id);

        if (! ($this->hasAccessUpdate() or $forumPost->creator_id == user()->id)) {
            return $this->message(trans('app.access_denied'));
        }

        /*
         * If the post is a root post, delete the thread instead of the post.
         */
        if ($forumPost->root) {
            // TODO: What to do? Redirect to the following?
            //return Redirect::action('forums/thread/update/'.$forumPost->forum_thread_id);
        }

        $forumPost->fill(Input::all());
        $forumPost->updater_id = user()->id;
        $valid = $forumPost->save();
        
        if (! $valid) {
            return Redirect::to('forums/threads/edit/'.$forumPost->id)
                ->withInput()->withErrors($forumPost->getErrors()); // TODO: Keep this redirect??
        }

        $this->messageFlash(trans('app.updated', ['Post']));

        return Redirect::to('forums/threads/'.$forumPost->thread->id.'/'.$forumPost->thread->slug);
    }

    /**
     * Deletes a post
     * 
     * @param int The id of the post
     */
    public function delete($id)
    {
        if (! $this->checkAccessDelete()) return;

        $forumPost = ForumPost::findOrFail($id);       

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
    }

}