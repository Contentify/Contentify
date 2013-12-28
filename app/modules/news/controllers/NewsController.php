<?php namespace App\Modules\News\Controllers;

use App\Modules\News\Models\News as News;
use URL;

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
        $allNews = News::where('title', 'LIKE', '%'.$subject.'%')->get();

        $results = array();
        foreach ($allNews as $news) {
            $results[$news->title] = URL::to('news/'.$news->id.'/show');
        }

        return $results;
    }

}