<?php

namespace App\Modules\News\Http\Controllers;

use App\Modules\News\Newscat;
use BackController;
use Hover;
use ModelHandlerTrait;

class AdminNewscatsController extends BackController
{

    use ModelHandlerTrait;

    protected $icon = 'newspaper';

    public function __construct()
    {
        $this->modelClass = Newscat::class;

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'tableHead' => [
                trans('app.id') => 'id', 
                trans('app.title') => 'title'
            ],
            'tableRow' => function($newscat)
            {
                /** @var Newscat $newscat */
                Hover::modelAttributes($newscat, ['image', 'creator']);

                return [
                    $newscat->id,
                    raw(Hover::pull(), $newscat->title)
                ];
            }
        ]);
    }

}