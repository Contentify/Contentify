<?php

namespace App\Modules\Contact\Http\Controllers;

use App\Modules\Contact\ContactMessage;
use BackController;
use HTML;
use Input;
use Mail;
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

    /**
     * Shows a contact message
     *
     * @param int $id
     * @throws \Exception
     */
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

    /**
     * Sends a reply to a given contact message
     *
     * @param int $id
     * @throws \Exception
     */
    public function reply($id)
    {
        /** @var ContactMessage $incomingMessage */
        $incomingMessage = ContactMessage::findOrFail($id);

        $replyText = Input::get('reply');

        Mail::raw($replyText, function(\Illuminate\Mail\Message $message) use ($incomingMessage)
        {
            $message->to($incomingMessage->email, $incomingMessage->username)->subject('RE: '.$incomingMessage->title);
        });

        $this->alertSuccess(trans('app.successful'));
        $this->show($id);
    }

}