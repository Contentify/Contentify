<?php

namespace App\Modules\News\Http\Controllers;

use App\Modules\News\News;
use BackController;
use Hover;
use HTML;
use ModelHandlerTrait;
use URL;

class AdminNewsController extends BackController
{

    use ModelHandlerTrait;

    protected $icon = 'newspaper';

    public function __construct()
    {
        $this->modelClass = News::class;

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'buttons'   => ['new', 'category'],
            'tableHead' => [
                trans('app.id')         => 'id', 
                trans('app.published')  => 'published', 
                trans('app.title')      => 'title', 
                trans('app.author')     => 'creator_id', 
                trans('app.created_at') => 'created_at'
            ],
            'tableRow'  => function(News $news)
            {
                Hover::modelAttributes($news, ['access_counter', 'updated_at']);

                return [
                    $news->id,
                    raw($news->published ? HTML::fontIcon('check') : HTML::fontIcon('times')),
                    raw(Hover::pull().HTML::link(URL::route('news.show', [$news->id]), $news->title)),
                    raw(HTML::link(URL::route('users.show', [$news->creator->id]), $news->creator->username)),
                    $news->created_at
                ];
            }
        ]);
    }

}
