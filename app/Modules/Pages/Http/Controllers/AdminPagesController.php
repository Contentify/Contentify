<?php namespace App\Modules\Pages\Http\Controllers;

use ModelHandlerTrait;
use App\Modules\Pages\Page;
use Hover, URL, HTML, BackController;

class AdminPagesController extends BackController {

    use ModelHandlerTrait;

    protected $icon = 'file';

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
                Hover::modelAttributes($page, ['access_counter']);

                switch ($page->pagecat_id) {
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
                    raw($page->published ? HTML::fontIcon('check') : null),
                    raw(Hover::pull().$link),
                    $page->pagecat->title,
                    raw(HTML::link(URL::route('users.show', [$page->creator->id]), $page->creator->username)),
                    $page->created_at
                ];
            }
        ]);
    }

}