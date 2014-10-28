<?php namespace App\Modules\News\Controllers;

use App\Modules\News\Models\News;
use Hover, HTML, URL, BackController;

class AdminNewsController extends BackController {

    protected $icon = 'newspaper.png';

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
                    $news->published ? HTML::image(asset('icons/accept.png'), trans('app.yes')) : '',
                    Hover::pull().HTML::link(URL::route('news.show', [$news->id]), $news->title),
                    HTML::link(URL::route('users.show', [$news->creator->id]), $news->creator->username),
                    $news->created_at
                ];
            }
        ]);
    }

}