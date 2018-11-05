<?php

namespace App\Modules\Messages\Http\Controllers;

use App\Modules\Messages\Message;
use FrontController;
use HTML;
use Illuminate\Database\Eloquent\Builder;

class InboxController extends FrontController
{

    public function __construct()
    {
        $this->modelClass = Message::class;

        parent::__construct();
    }

    public function index()
    {
        $this->pageView('messages::page_navigation', ['active' => 'inbox']);

        $this->indexPage([
            'buttons'       => null,
            'tableHead'     => [
                trans('app.new')        => 'new', 
                trans('app.title')      => 'title', 
                trans('app.creator')    => 'creator_id',
                trans('app.date')       => 'created_at',
            ],
            'tableRow'      => function(Message $message)
            {
                $icon = HTML::fontIcon('times');
                if ($message->new) {
                    $icon = HTML::fontIcon('check');
                }

                $creator = $message->creator;

                return [
                    raw($icon),
                    raw(link_to('messages/'.$message->id.'/'.$message->slug, $message->title)),
                    raw(link_to('users/'.$creator->id.'/'.$creator->slug, $creator->username)),
                    $message->created_at->dateTime(),
                ];
            },
            'actions'       => null,
            'permaFilter'   => function(Builder $query)
            {
                 return $query->whereReceiverId(user()->id)->whereReceiverVisible(true);
            },
            'brightenFirst' => false,
            'pageTitle'     => false,
        ], 'front');
    }

}