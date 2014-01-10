<?php namespace App\Modules\News\Controllers;

use App\Modules\News\Models\News as News;
use HTML, BackController;

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
            'tableHead' => [t('ID') => 'id', t('Published') => 'published', t('Title') => 'title', t('Author') => 'creator_id', t('Created at') => 'created_at'],
            'tableRow'  => function($news)
            {
                return array(
                    $news->id,
                    $news->published ? HTML::image(asset('icons/accept.png'), 'True') : '',
                    $news->title,
                    $news->creator->username,
                    $news->created_at->toDateString()
                    );
            }
            ));
    }

}