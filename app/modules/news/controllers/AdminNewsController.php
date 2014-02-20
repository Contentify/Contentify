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
                t('ID')         => 'id', 
                t('Published')  => 'published', 
                t('Title')      => 'title', 
                t('Author')     => 'creator_id', 
                t('Created at') => 'created_at'
            ],
            'tableRow'  => function($news)
            {
                return [
                    $news->id,
                    $news->published ? HTML::image(asset('icons/accept.png'), 'True') : '',
                    HTML::link(URL::route('news.show', [$news->id]), $news->title),
                    $news->creator->username,
                    $news->created_at->toDateString()
                ];
            }
        ]);
    }

}