<?php

namespace App\Modules\Images\Http\Controllers;

use App\Modules\Images\Image;
use Input, DB, View, BaseController;

class EditorImagesController extends BaseController {

    /**
     * Returns the latest images
     * 
     * @return View
     */
    public function index()
    {
        if (! $this->checkAccessUpdate()) return Response::make(null, 403);

        $images = Image::orderBy('created_at', 'desc')->take(9)->get();

        $pure = false;
        return View::make('images::editor_images', compact('images', 'pure'));
    }

    /**
     * Returns images by tags
     * 
     * @return View
     */
    public function search()
    {
        if (! $this->checkAccessUpdate()) return Response::make(null, 403);
        
        $tag = Input::get('tag');

        $images = Image::where('tags', 'LIKE', '%'.$tag.'%')->orderBy('created_at', 'desc')->get();

        $pure = true;
        return View::make('images::editor_images', compact('images', 'pure'));
    }

}