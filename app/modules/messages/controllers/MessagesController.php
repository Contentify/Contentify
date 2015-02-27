<?php namespace App\Modules\Messages\Controllers;

use App\Modules\Messages\Models\Message;
use Cache, User, Input, Redirect, FrontController;

class MessagesController extends FrontController {

    public function show($id)
    {
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

    public function create($username = null)
    {
        $this->pageView('messages::form', ['username' => $username]);
    }

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

    public function reply($id)
    {
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

    public function destroy($id)
    {
        $message = Message::findOrFail($id);

        if ($message->receiver_id != user()->id and $message->creator_id != user()->id) {
            $this->alertError(trans('app.access_denied'));
            return;
        }

        if ($message->creator_id == user()->id) {
            $message->creator_visible = false;
        }

        if ($message->receiver_id == user()->id) {
            $message->receiver_visible = false;
        }

        if (! $message->creator_visible and ! $message->receiver_visible) {
            Message::destroy($id);
        }

        $this->alertFlash(trans('app.deleted', ['Message']));
        return Redirect::to('messages/inbox');
    }
    
}