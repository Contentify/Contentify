<?php namespace App\Modules\Messages\Controllers;

use App\Modules\Messages\Models\Message;
use Input, Redirect, FrontController;

class MessagesController extends FrontController {

    public function show($id)
    {
        $message = Message::findOrFail($id);

        if ($message->receiver_id != user()->id and $message->creator_id != user()->id) {
            $this->message(trans('app.access_denied'));
            return;
        }

        if ($message->receiver_id == user()->id) {
            $message->new = false;
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

    public function post()
    {
        $message = new Message(Input::all());

        $message->creator_id = user()->id;
        $message->updater_id = user()->id;
        $message->createSlug();
        $okay = $message->setReceiverByName(Input::get('receiver_name'));

        $okay = $message->save();

        if ($okay) {
            $this->messageFlash(trans('messages::message_sent'));
            return Redirect::to('messages/outbox');
        } else {
            return Redirect::to('messages/create')->withInput()->withErrors($message->getErrors());
        }
    }

    public function reply($id)
    {
        $message = Message::findOrFail($id);

        if ($message->receiver_id != user()->id) {
            $this->message(trans('app.access_denied'));
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
            $this->message(trans('app.access_denied'));
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

        $this->messageFlash(trans('app.deleted', ['Message']));
        return Redirect::to('messages/inbox');
    }
    
}