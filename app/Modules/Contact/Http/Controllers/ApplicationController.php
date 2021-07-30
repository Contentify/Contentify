<?php

namespace App\Modules\Contact\Http\Controllers;

use App\Modules\Contact\ContactMessage;
use App\Modules\Contact\ApplicationMessage;
use App\Modules\Teams\Team;
use FrontController;
use Request;
use Redirect;

class ApplicationController extends FrontController
{

    public function index()
    {
        $this->pageView('contact::application_form');
    }

    public function store()
    {
        $msg = new ApplicationMessage(Request::all());

        $okay = $msg->isValid();

        if ($okay) {
            /** @var Team $team */
            $team = Team::findOrFail(Request::get('team_id'));

            $msg = new ContactMessage(Request::all());
            $msg->ip = $msg->ip = getenv('REMOTE_ADDR');
            $msg->title = 'Application of '.$msg->username;
            $msg->text = trans('app.object_team').': '.$team->title.PHP_EOL.PHP_EOL.
                trans('app.role').': '.Request::get('role').PHP_EOL.PHP_EOL.PHP_EOL.
                trans('contact::application').': '.PHP_EOL.PHP_EOL.
                $msg->text;

            $msg->forceSave();
            $msg->notify();

            $this->alertSuccess(trans('contact::message_sent'));
        } else {
            return Redirect::to('application')->withInput()->withErrors($msg->getErrors());
        }
    }
}
