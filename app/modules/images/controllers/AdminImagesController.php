<?php namespace App\Modules\Images\Controllers;

use App\Modules\Images\Models\Image as Image;
use HTML, BackController;

class AdminImagesController extends BackController {

    protected $icon = 'picture.png';

	public function __construct()
	{
		$this->model = 'Image';

		parent::__construct();
	}

    public function index()
    {
        $this->buildIndexForm([
            'tableHead' => [
                t('ID')         => 'id', 
                t('Image')      => null, 
                t('Created at') => 'created_at'
            ],
            'tableRow' => function($image)
            {
                $imgCode = HTML::image(
                    asset('uploads/images/100/'.$image->image), 
                    'Image-Preview', ['class' => 'image']
                );
                $preview = '<a href="'.asset('uploads/images/'.$image->image).'">'.$imgCode.'</a><br>'.$image->tags;

                return [
                    $image->id,
                    $preview,
                    $image->created_at->toDateString()
                ];
            },
            'searchFor' => 'tags'
        ]);
    }

}