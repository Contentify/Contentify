<?php namespace App\Modules\News\Http\Controllers;

use ModelHandlerTrait;
use App\Modules\News\News;
use Hover, HTML, URL, BackController;

class AdminNewsController extends BackController {

    use ModelHandlerTrait;

    protected $icon = 'newspaper-o';

    public function __construct()
    {
        $this->modelName = 'News';

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
            'tableRow'  => function($news)
            {
                Hover::modelAttributes($news, ['access_counter']);

                return [
                    $news->id,
                    raw($news->published ? HTML::fontIcon('check') : null),
                    raw(Hover::pull().HTML::link(URL::route('news.show', [$news->id]), $news->title)),
                    raw(HTML::link(URL::route('users.show', [$news->creator->id]), $news->creator->username)),
                    $news->created_at
                ];
            }
        ]);
    }

}