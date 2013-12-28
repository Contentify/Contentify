<?php namespace App\Modules\News\Controllers;

use App\Modules\News\Models\News as News;

class NewsController extends \FrontController {

	public function __construct()
	{
		//$this->form['model'] = 'News';

		//parent::__construct();
	}

    public function index()
    {
        return 'News.index called';
    }

    public function search($subject)
    {
        $news = News::where('title', 'LIKE', '%'.$subject.'%')->get();

        return $news;
    }

}