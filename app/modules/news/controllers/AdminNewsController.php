<?php namespace App\Modules\News\Controllers;

use App\Modules\News\Models\News as News;
use BackController;

class AdminNewsController extends BackController {

    protected $icon = 'newspaper.png';

	public function __construct()
	{
		$this->model = 'News';

		parent::__construct();
	}

    public function index()
    {
        $this->buildIndexForm(array(
            'buttons'   => ['new', 'category'],
            'tableHead' => [t('ID') => 'id', t('Title') => 'title'],
            'tableRow'  => function($news)
            {
                return array(
                    $news->id,
                    $news->title
                    );
            }
            ));
    }

}