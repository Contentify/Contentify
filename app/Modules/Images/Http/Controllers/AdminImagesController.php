<?php

namespace App\Modules\Images\Http\Controllers;

use App\Modules\Images\Image;
use BackController;
use ModelHandlerTrait;
use Request;

class AdminImagesController extends BackController
{

    use ModelHandlerTrait;

    /**
     * Number of items per page on the admin index page
     */
    const ITEMS_PER_PAGE = 15;

    protected $icon = 'image';

    public function __construct()
    {
        $this->modelClass = Image::class;

        parent::__construct();
    }

    public function index()
    {

        if (Request::old('search')) {
            $searchString = Request::old('search');
            $images = Image::where('tags', 'LIKE', '%'.$searchString.'%')
                ->orderBy('created_at', 'desc')->paginate(self::ITEMS_PER_PAGE);
        } else {
            $searchString = null;
            $images = Image::orderBy('created_at', 'desc')->paginate(self::ITEMS_PER_PAGE);
        }

        $this->pageView('images::admin_index', compact('images', 'searchString'));
    }
}
