<?php namespace App\Modules\Galleries\Controllers;

use App\Modules\Images\Models\Image;
use App\Modules\Galleries\Models\Gallery;
use View, Widget;

class GalleriesWidget extends Widget {

    public function render($parameters = array())
    {
        if (isset($parameters['categoryId'])) {
            $categoryId = (int) $parameters['categoryId'];
        } else {
            $categoryId = null;
        }

        if (isset($parameters['random'])) {
            $random = (bool) $parameters['random'];;
        } else {
            $random = false;
        }

        if ($random) {
            $images = Image::whereNotNull('gallery_id')->orderBy(DB::raw('RAND()'))->take(5)->get();
        } else {
            if ($categoryId !== null) {
                $images = Image::whereGalleryId($categoryId)->orderBy('created_at', 'DESC')->take(5)->get();
            } else {
                $images = Image::whereNotNull('gallery_id')->orderBy('created_at', 'DESC')->take(5)->get();
            }
        }

        return View::make('galleries::widget', compact('images'))->render();
    }

}