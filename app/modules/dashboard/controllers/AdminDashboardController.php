<?php namespace App\Modules\Dashboard\Controllers;

use View, Cache, BackController;

class AdminDashboardController extends BackController {

    /**
     * Feed URL
     */
    const FEED_URL = 'http://www.contentify.it/share/feeds/cms.json';

    protected $icon = 'house.png';

    public function getIndex()
    {
        $feed = $this->feed();

        $this->pageView('dashboard::admin_index', compact('feed'));
    }

    /**
     * Receive feed, render feed view, cache it and return the HTML code.
     * 
     * @return string
     */
    public function feed()
    {  
        $key = 'dashboard.feedMessages';

        if (Cache::has($key)) {
            $view = Cache::get($key);
        } else {
            // Note: File::get() can't access remote targets so we have to use the PHP function.
            $content = file_get_contents(self::FEED_URL);

            $messages = json_decode($content);

            $view = View::make('dashboard::feed', compact('messages'))->render();

            Cache::put($key, $view, 60 * 12);           
        }        

        return $view;
    }

}