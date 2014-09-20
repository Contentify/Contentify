<?php namespace App\Modules\Galleries\Controllers;

use App\Modules\Images\Models\Image;
use App\Modules\Galleries\Models\Gallery;
use View, Widget;

class GalleriesWidget extends Widget {

    public function render($parameters = array())
    {
    	$images = Image::whereNotNull('gallery_id')->orderBy('created_at', 'DESC')->take(5)->get();

        return View::make('galleries::widget', compact('images'))->render();
    }

}