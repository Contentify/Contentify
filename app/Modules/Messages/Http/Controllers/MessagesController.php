<?php

namespace App\Modules\Messages\Http\Controllers;

use App\Modules\Messages\Message;
use Cache;
use FrontController;
use Input;
use Redirect;
use User;

class MessagesController extends FrontController
{

    /**
     * Show a message
     *
     * @param int $id The ID of the message
     */
    public function show($id)
    {
        /** @var Message $message */
        $message = Message::findOrFail($id);

        if ($message->receiver_id != user()->id and $message->creator_id != user()->id) {
            $this->alertError(trans('app.access_denied'));
            return;
        }

        if ($message->receiver_id == user()->id) {
            $message->new = false;

            // Reset the message counter cache of the receiving user:
            Cache::forget(User::CACHE_KEY_MESSAGES.user()->id);
        }

        $message->access_counter++;
        $message->save();

        $this->title($message->title);

        $this->pageView('messages::show', compact('message'));
    }

    /**
     * Show the page with the form to create a new message
     *
     * @param string|null $username Optional: Name of the receiver
     */
    public function create($username = null)
    {
        $this->pageView('messages::form', ['username' => $username]);
    }

    /**
     * Store a new message
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        $message = new Message(Input::all());

        $message->creator_id = user()->id;
        $message->updater_id = user()->id;
        $message->createSlug();
        $message->setReceiverByName(Input::get('receiver_name'));

        $okay = $message->save();

        if ($okay) {
            // Reset the message counter cache of the receiving user:
            Cache::forget(User::CACHE_KEY_MESSAGES.$message->receiver_id);

            $this->alertFlash(trans('messages::message_sent'));
            return Redirect::to('messages/outbox');
        } else {
            return Redirect::to('messages/create')->withInput()->withErrors($message->getErrors());
        }
    }

    /**
     * Create a reply for a message
     *
     * @param int $id The ID of the message
     * @return void
     */
    public function reply($id)
    {
        /** @var Message $message */
        $message = Message::findOrFail($id);

        if ($message->receiver_id != user()->id) {
            $this->alertError(trans('app.access_denied'));
            return;
        }

        $data = [
            'username'  => $message->creator->username, 
            'title'     => trans('messages::re').$message->title, 
            'text'      => '[quote='.$message->creator->username.']'.$message->text.'[/quote]'
        ];

        $this->pageView('messages::form', $data);
    }

    /**
     * Destroy a message (if allowed to)
     *
     * @param int $id The ID of the message
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function destroy($id)
    {
        /** @var Message $message */
        $message = Message::findOrFail($id);

        if ($message->receiver_id != user()->id and $message->creator_id != user()->id) {
            $this->alertError(trans('app.access_denied'));
            return null;
        }

        if ($message->creator_id == user()->id) {
            $message->creator_visible = false;
            $message->save();
        }

        if ($message->receiver_id == user()->id) {
            $message->receiver_visible = false;
            $message->save();
        }

        if ((! $message->creator_visible or $message->sent_by_system) and ! $message->receiver_visible) {
            Message::destroy($id);
        }

        $this->alertFlash(trans('app.deleted', ['Message']));
        return Redirect::to('messages/inbox');
    }
    
}