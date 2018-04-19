<?php

namespace App\Modules\Images\Http\Controllers;

use App\Modules\Images\Image;
use BackController;
use Input;
use ModelHandlerTrait;

class AdminImagesController extends BackController
{

    use ModelHandlerTrait;

    protected $icon = 'image';

    public function __construct()
    {
        $this->modelClass = Image::class;

        parent::__construct();
    }

    public function index()
    {
        $perPage = 15;

        if (Input::old('search')) {
            $searchString = Input::old('search');
            $images = Image::where('tags', 'LIKE', '%'.$searchString.'%')
                ->orderBy('created_at', 'desc')->paginate($perPage);
        } else {
            $searchString = null;
            $images = Image::orderBy('created_at', 'desc')->paginate($perPage);
        }

        $this->pageView('images::admin_index', compact('images', 'searchString'));
    }

}