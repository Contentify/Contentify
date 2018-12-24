<?php

namespace App\Modules\Comments\Http\Controllers;

use Contentify\Models\Comment;
use BackController;
use HTML;
use ModelHandlerTrait;
use URL;

class AdminCommentsController extends BackController
{

    use ModelHandlerTrait;

    protected $icon = 'comment';

    public function __construct()
    {
        $this->modelClass = Comment::class;

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'buttons'   => [],
            'searchFor' => 'text',
            'limit'     => 10,
            'tableHead' => [
                trans('app.id')         => 'id',
                trans('app.text')       => 'text',
                trans('app.type')       => 'foreign_type',
                trans('app.author')     => 'creator_id',
                trans('app.created_at') => 'created_at'
            ],
            'tableRow' => function(Comment $comment)
            {
                return [
                    $comment->id,
                    $comment->plainText(),
                    $comment->foreign_type.'&nbsp;#'.$comment->foreign_id,
                    raw(HTML::link(URL::route('users.show', [$comment->creator->id]), $comment->creator->username)),
                    $comment->created_at->dateTime(),
                ];
            },
            'actions' => ['delete']
        ]);
    }

}
