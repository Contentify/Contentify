<?php namespace App\Modules\Images\Controllers;

use App\Modules\Images\Models\Image;
use HTML, BackController;

class AdminImagesController extends BackController {

    protected $icon = 'picture.png';

    public function __construct()
    {
        $this->modelName = 'Image';

        parent::__construct();
    }

    public function index()
    {
        $this->indexPage([
            'tableHead' => [
                trans('app.id')         => 'id', 
                trans('app.image')      => null,
                trans('app.created_at') => 'created_at'
            ],
            'tableRow' => function($image)
            {
                $imgCode = HTML::image(
                    $image->uploadPath().'100/'.$image->image, 
                    'Image-Preview', ['class' => 'image']
                );
                $preview = '<a href="'.$image->uploadPath().$image->image.'">'.$imgCode.'</a><br>'.$image->tags;

                return [
                    $image->id,
                    $preview,
                    $image->created_at
                ];
            },
            'searchFor' => 'tags'
        ]);
    }

}