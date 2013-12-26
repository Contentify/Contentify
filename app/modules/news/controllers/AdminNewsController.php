<?php namespace App\Modules\News\Controllers;

use App\Modules\News\Models\Game as Game;

class AdminNewsController extends \BackController {

	public function __construct()
	{
		$this->form['model'] = 'News';

		parent::__construct();
	}

    public function index()
    {
        $this->buildIndexForm(array(
            'tableHead' => [t('ID') => 'id', t('Title') => 'title'],
            'tableRow' => function($news)
            {
                return array(
                    $news->id,
                    $news->title
                    );
            }
            ));
    }

}