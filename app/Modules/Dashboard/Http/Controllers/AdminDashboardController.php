<?php

namespace App\Modules\Dashboard\Http\Controllers;

use BackController;
use Cache;
use Config;
use HTML;
use Log;
use View;

class AdminDashboardController extends BackController
{

    /**
     * Feed URL
     */
    const FEED_URL = 'http://www.contentify.org/share/feeds/cms.json';

    /**
     * Show a warning when there is less free disk space than defined in this constant
     */
    const MIN_FREE_DISK_SPACE = 100 /* MB */ * 1024 /* KB */ * 1024 /* B */;

    protected $icon = 'home';

    public function getIndex()
    {
        $feed = $this->feed();

        if (Config::get('app.env') == 'production' and Config::get('app.debug') and $_SERVER['HTTP_HOST'] != 'localhost') {
            $this->alertWarning(trans('app.debug_warning').' '.HTML::link(
                'https://github.com/Contentify/Contentify/wiki/FAQ#how-can-i-disable-the-debug-mode',
                trans('app.read_more')));
        }

        if (function_exists('disk_free_space') and disk_free_space('.') < self::MIN_FREE_DISK_SPACE) {
            $freeSpace = round(disk_free_space('.') / 1024 / 1024).'M';
            $this->alertWarning(trans('app.space_warning', [$freeSpace]));
        }

        $this->pageView('dashboard::admin_index', compact('feed'));
    }

    /**
     * Receive feed, render feed view, cache it and return the HTML code.
     * 
     * @return string|null
     */
    public function feed()
    {
        $key = 'dashboard::feedMessages';

        if (Cache::has($key)) {
            $view = Cache::get($key);
        } else {
            // Note: File::get() can't access remote targets so we have to use the PHP function.
            $content = @file_get_contents(self::FEED_URL);
            
            if ($content === false) {
                // Create an empty key to avoid incessant fetching attempts 
                Cache::put($key, '', 10);

                Log::warning("Failed to fetch dashboard message feed '".self::FEED_URL."'");

                return null;
            }

            $messages = json_decode($content);

            $view = View::make('dashboard::feed', compact('messages'))->render();

            Cache::put($key, $view, 60 * 6);
        }

        return $view;
    }

}
