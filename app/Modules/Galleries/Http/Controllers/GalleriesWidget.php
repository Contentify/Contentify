<?php

namespace App\Modules\Galleries\Http\Controllers;

use App\Modules\Images\Image;
use App\Modules\Galleries\Gallery;
use View, Widget;

class GalleriesWidget extends Widget {

    public function render($parameters = array())
    {
        $limit = isset($parameters['limit']) ? (int) $parameters['limit'] : self::LIMIT;
        
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
            $images = Image::whereNotNull('gallery_id')->orderBy(DB::raw('RAND()'))->take($limit)->get();
        } else {
            if ($categoryId !== null) {
                $images = Image::whereGalleryId($categoryId)->orderBy('created_at', 'DESC')->take($limit)->get();
            } else {
                $images = Image::whereNotNull('gallery_id')->orderBy('created_at', 'DESC')->take($limit)->get();
            }
        }

        return View::make('galleries::widget', compact('images'))->render();
    }

}