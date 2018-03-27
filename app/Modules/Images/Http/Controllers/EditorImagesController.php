<?php

namespace App\Modules\Images\Http\Controllers;

use App\Modules\Images\Image;
use BaseController;
use Input;
use Response;
use View;

class EditorImagesController extends BaseController
{

    /**
     * Returns the latest images
     * 
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        if (! $this->checkAccessUpdate()) {
            return Response::make(null, 403);
        }

        $images = Image::orderBy('created_at', 'desc')->take(9)->get();

        $pure = false;
        return View::make('images::editor_images', compact('images', 'pure'));
    }

    /**
     * Returns images by tags
     * 
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function search()
    {
        if (! $this->checkAccessUpdate()) {
            return Response::make(null, 403);
        }
        
        $tag = Input::get('tag');

        $images = Image::where('tags', 'LIKE', '%'.$tag.'%')->orderBy('created_at', 'desc')->get();

        $pure = true;
        return View::make('images::editor_images', compact('images', 'pure'));
    }

}