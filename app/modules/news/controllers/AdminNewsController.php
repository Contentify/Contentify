<?php namespace App\Modules\News\Controllers;

use App\Modules\News\Models\News as News;
use HTML, URL, BackController;

class AdminNewsController extends BackController {

    protected $icon = 'newspaper.png';

    public function __construct()
    {
        $this->model = 'News';

        parent::__construct();
    }

    public function index()
    {
        $this->buildIndexPage([
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
                return [
                    $news->id,
                    $news->published ? HTML::image(asset('icons/accept.png'), 'True') : '',
                    HTML::link(URL::route('news.show', [$news->id]), $news->title),
                    HTML::link(URL::route('users.show', [$news->creator->id]), $news->creator->username),
                    $news->created_at->toDateString()
                ];
            }
        ]);
    }

}