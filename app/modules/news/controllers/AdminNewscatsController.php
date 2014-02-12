<?php namespace App\Modules\News\Controllers;

use App\Modules\News\Models\Newscat as Newscat;
use BackController;

class AdminNewscatsController extends BackController {

    protected $icon = 'newspaper.png';

	public function __construct()
	{
		$this->model = 'Newscat';

		parent::__construct();
	}

    public function index()
    {
        $this->buildIndexForm([
            'tableHead' => [t('ID') => 'id', t('Title') => 'title'],
            'tableRow' => function($newscat)
            {
                return [
                    $newscat->id,
                    $newscat->title
                ];
            }
        ]);
    }

}