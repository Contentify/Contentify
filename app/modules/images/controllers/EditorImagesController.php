<?php namespace App\Modules\Images\Controllers;

use App\Modules\Images\Models\Image;
use Input, DB, View, BaseController;

class EditorImagesController extends BaseController {

    /**
     * Returns the latest images
     * 
     * @return View
     */
    public function index()
    {
        // TODO: Permission check

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
        // TODO: Permission check
        
        $tag = Input::get('tag');

        $images = Image::where('tags', 'LIKE', '%'.$tag.'%')->orderBy('created_at', 'desc')->get();

        $pure = true;
        return View::make('images::editor_images', compact('images', 'pure'));
    }

}