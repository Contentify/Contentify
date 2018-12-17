<?php

namespace App\Modules\Pages\Http\Controllers;

use App\Modules\Pages\Page;
use BackController;
use Hover;
use HTML;
use ModelHandlerTrait;
use URL;

class AdminPagesController extends BackController
{

    use ModelHandlerTrait;

    protected $icon = 'file';

    public function __construct()
    {
        $this->modelClass = Page::class;

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'tableHead' => [
                trans('app.id')         => 'id', 
                trans('app.published')  => 'published', 
                trans('app.title')      => 'title', 
                trans('app.category')   => 'page_cat_id',
                trans('app.author')     => 'creator_id', 
                trans('app.created_at') => 'created_at'
            ],
            'tableRow'  => function(Page $page)
            {
                Hover::modelAttributes($page, ['access_counter', 'updated_at']);

                switch ($page->page_cat_id) {
                    case '1':
                        $link = HTML::link(URL::route('articles.show', [$page->id]), $page->title);
                        break;
                    case '2':
                        $link = HTML::link(URL::route('pages.showSlug', [$page->slug]), $page->title);
                        break;
                    default:
                        $link = e($page->title);
                }

                return [
                    $page->id,
                    raw($page->published ? HTML::fontIcon('check') : HTML::fontIcon('times')),
                    raw(Hover::pull().$link),
                    $page->pageCat->title,
                    raw(HTML::link(URL::route('users.show', [$page->creator->id]), $page->creator->username)),
                    $page->created_at
                ];
            }
        ]);
    }

}
