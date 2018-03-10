<?php

namespace App\Modules\Contact\Http\Controllers;

use App\Modules\Contact\ContactMessage;
use ModelHandlerTrait;
use HTML, BackController;

class AdminContactController extends BackController
{

    use ModelHandlerTrait;

    protected $icon = 'envelope';

    public function __construct()
    {
        $this->modelName = 'ContactMessage';

        parent::__construct();
    }

    public function index()
    {
        if (! $this->checkAccessRead()) return;

        $this->indexPage([
            'buttons' => null,
            'tableHead' => [
                trans('app.id')         => 'id', 
                trans('app.new')        => 'new', 
                trans('app.title')      => 'title', 
                trans('app.creator')    => 'username', 
                trans('app.created_at') => 'created_at'
            ],
            'tableRow' => function($msg)
            {
                return [
                    $msg->id,
                    raw($msg->new ? 
                        HTML::fontIcon('envelope') : 
                        null),
                    raw(link_to_route('admin.contact.show', $msg->title, [$msg->id])),
                    $msg->username,
                    $msg->created_at,
                ];
            },
            'actions' => ['delete', 'restore']
        ]);
    }

    public function show($id)
    {
        if (! $this->checkAccessRead()) return;
        
        $msg = ContactMessage::findOrFail($id);

        $msg->new = false;
        $msg->save();

        $this->pageView('contact::admin_show', compact('msg'));
    }

}