<?php namespace App\Modules\Pages\Controllers;

use App\Modules\Pages\Models\Page;
use URL, HTML, BackController;

class AdminPagesController extends BackController {

    protected $icon = 'doc_offlice.png';

    public function __construct()
    {
        $this->modelName = 'Page';

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'tableHead' => [
                trans('app.id')         => 'id', 
                trans('app.published')  => 'published', 
                trans('app.title')      => 'title', 
                trans('app.category')   => 'pagecat_id',
                trans('app.author')     => 'creator_id', 
                trans('app.created_at') => 'created_at'
            ],
            'tableRow'  => function($page)
            {
                return [
                    $page->id,
                    $page->published ? HTML::image(asset('icons/accept.png'), 'True') : '',
                    $page->title, //HTML::link(URL::route('page.show', [$page->id]), $page->title),
                    $page->pagecat->title,
                    HTML::link(URL::route('users.show', [$page->creator->id]), $page->creator->username),
                    $page->created_at
                ];
            }
        ]);
    }

}