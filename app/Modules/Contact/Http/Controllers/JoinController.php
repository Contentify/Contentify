<?php

namespace App\Modules\Contact\Http\Controllers;

use App\Modules\Contact\ContactMessage;
use App\Modules\Contact\JoinMessage;
use App\Modules\Teams\Team;
use FrontController;
use Input;
use Redirect;

class JoinController extends FrontController
{

    public function index()
    {
        $this->pageView('contact::join_form');
    }

    public function store()
    {
        $msg = new JoinMessage(Input::all());
        $msg->ip = getenv('REMOTE_ADDR');

        $okay = $msg->isValid();
        
        if ($okay) {
            $team = Team::findOrFail(Input::get('team_id'));
            $msg = new ContactMessage(Input::all());
            $msg->title = 'Application of '.$msg->username;
            $msg->text = '<strong>Team:</strong> '.$team->title.'<br><br>'.
                '<strong>'.trans('app.role').':</strong> '.Input::get('role').'<br><br>'.
                '<strong>'.trans('contact::application').':</strong> <br><br>'. $msg->text;
            $msg->forceSave();
            $msg->notify();

            $this->alertSuccess(trans('contact::message_sent'));
        } else {
            return Redirect::to('join-us')->withInput()->withErrors($msg->getErrors());
        }
    }
    
}