<?php namespace App\Modules\News\Models;

use Ardent, Rss, Config, Lang, URL;

class News extends Ardent {

    protected $softDelete = true;

    protected $fillable = array('title', 'intro', 'text', 'published', 'internal', 'allow_comments', 'newscat_id');

    public static $rules = array(
        'title'   => 'required',
    );

    public static $relationsData = array(
        'newscat' => array(self::BELONGS_TO, 'App\Modules\News\Models\Newscat'),
        'creator' => array(self::BELONGS_TO, 'User'),
    );

    /**
     * Create/update RSS file
     * 
     * @return void
     */
    public static function updateRSS() 
    {
        $feed = Rss::feed('2.0', 'UTF-8');

        $feed->channel(array(
            'title'         => Config::get('app.title').' News', 
            'description'   => 'Last 20 News', 
            'language'      => Lang::getLocale(),
            'link'          => Config::get('app.url'),
            'lastBuildDate' => date('D, j M Y H:i:s ').'GMT'
        ));

        $newsCollection = News::orderBy('created_at', 'DESC')->take(20)->get();
        foreach ($newsCollection as $news) {
            $url = URL::route('news.show', $news->id);

            $feed->item(array(
                'title'             => $news->title, 
                'description|cdata' => $news->intro, 
                'link'              => $url,
                'guid'              => $url,
                'pubDate'           => date('D, j M Y H:i:s ', $news->created_at->timestamp).'GMT'
            ));
        }

        $feed->save(public_path().'/rss/news.xml');
    }

    public function afterSave()
    {
        self::updateRSS();
    }

    public function afterDelete()
    {
        self::updateRSS();
    }
}