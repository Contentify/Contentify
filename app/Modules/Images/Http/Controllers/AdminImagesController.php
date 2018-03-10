<?php

namespace App\Modules\Images\Http\Controllers;

use App\Modules\Images\Image;
use ModelHandlerTrait;
use Input, BackController;

class AdminImagesController extends BackController
{

    use ModelHandlerTrait;

    protected $icon = 'image';

    public function __construct()
    {
        $this->modelName = 'Image';

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