<?php

namespace App\Modules\Contact\Http\Controllers;

use App\Modules\Contact\ContactMessage;
use BackController;
use HTML;
use ModelHandlerTrait;

class AdminContactController extends BackController
{

    use ModelHandlerTrait;

    protected $icon = 'envelope';

    public function __construct()
    {
        $this->modelClass = ContactMessage::class;

        parent::__construct();
    }

    public function index()
    {
        if (! $this->checkAccessRead()) {
            return;
        }

        $this->indexPage([
            'buttons' => null,
            'tableHead' => [
                trans('app.id')         => 'id', 
                trans('app.new')        => 'new', 
                trans('app.title')      => 'title', 
                trans('app.creator')    => 'username', 
                trans('app.created_at') => 'created_at'
            ],
            'tableRow' => function(ContactMessage $message)
            {
                return [
                    $message->id,
                    raw($message->new ?
                        HTML::fontIcon('envelope') : 
                        null),
                    raw(link_to_route('admin.contact.show', $message->title, [$message->id])),
                    $message->username,
                    $message->created_at,
                ];
            },
            'actions' => ['delete', 'restore']
        ]);
    }

    public function show($id)
    {
        if (! $this->checkAccessRead()) {
            return;
        }

        /** @var ContactMessage $msg */
        $msg = ContactMessage::findOrFail($id);

        $msg->new = false;
        $msg->save();

        $this->pageView('contact::admin_show', compact('msg'));
    }

}