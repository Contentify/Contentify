<?php 

namespace App\Modules\Slides\Http\Controllers;

use App\Modules\Slides\Slide;
use App\Modules\Slides\SlideCat;
use View;
use Widget;

class SlidesWidget extends Widget
{

    public function render(array $parameters = array())
    {
        if (isset($parameters['categoryId'])) {
            $categoryId = $parameters['categoryId'];
        } else {
            $slideCat = SlideCat::first();
            if ($slideCat) {
                $categoryId = $slideCat->id;
            } else {
                $categoryId = 0;
            }
        }

        $slides = Slide::whereSlideCatId($categoryId)->published()->orderBy('position', 'ASC')->get();

        return View::make('slides::widget', compact('slides', 'categoryId'))->render();
    }

}