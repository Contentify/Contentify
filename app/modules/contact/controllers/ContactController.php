<?php namespace App\Modules\Contact\Controllers;

use App\Modules\Contact\Models\ContactMessage;
use Input, Redirect, FrontController;

class ContactController extends FrontController {

    public function getIndex()
    {
        $this->pageView('contact::form');
    }

    public function postStore()
    {
        $msg = new ContactMessage(Input::all());
        $msg->ip = getenv('REMOTE_ADDR');

        $okay = $msg->save();
        
        if ($okay) {
            $this->message(trans('contact::message_sent'));
        } else {
            return Redirect::to('contact')->withInput()->withErrors($msg->validationErrors);
        }
    }
}