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
        $this->buildIndexForm(array(
            'tableHead' => [t('ID') => 'id', t('Tags') => 'tags'],
            'tableRow' => function($image)
            {
                return array(
                    $image->id,
                    HTML::image(asset('uploads/images/100/'.$image->image), 'Image-Preview', ['class' => 'image']).'<br>'.$image->tags
                    );
            }
            ));
    }

}