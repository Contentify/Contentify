<?php namespace App\Modules\Contact\Controllers;

use App\Modules\Contact\Models\ContactMessage;
use HTML, View, BackController;

class AdminContactController extends BackController {

    protected $icon = 'email_open.png';

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
                trans('app.ID')         => 'id', 
                trans('contact::new')   => 'new', 
                trans('app.title')      => 'title', 
                trans('app.creator')    => 'username', 
                trans('app.created_at') => 'created_at'
            ],
            'tableRow' => function($msg)
            {
                return [
                    $msg->id,
                    $msg->new ? HTML::image(asset('icons/email_go.png')) : HTML::image(asset('icons/email_open.png')),
                    link_to_route('admin.contact.show', $msg->title, [$msg->id]),
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

        return $this->pageView('contact::admin_show', compact('msg'));
    }

}