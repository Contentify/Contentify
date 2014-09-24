<?php namespace App\Modules\News\Models;

use SoftDeletingTrait, ContentFilter, DB, OpenGraph, Comment, Rss, Config, Lang, URL, BaseModel;

class News extends BaseModel {

    use SoftDeletingTrait;

    protected $dates = ['deleted_at', 'published_at'];

    protected $slugable = true;

    protected $fillable = [
        'title', 
        'intro', 
        'text', 
        'published', 
        'published_at',
        'internal', 
        'enable_comments', 
        'newscat_id', 
        'creator_id'
    ];

    public static $rules = [
        'title'   => 'required',
    ];

    public static $relationsData = [
        'newscat' => [self::BELONGS_TO, 'App\Modules\News\Models\Newscat'],
        'creator' => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

    public static function boot()
    {
        parent::boot();

        self::saved(function($news)
        {
           self::updateRSS();
        });

        self::deleted(function($news)
        {
           self::updateRSS();
        });
    }

    public function scopeFilter($query)
    {
        if (ContentFilter::has('newscat_id')) {
            $id = (int) ContentFilter::get('newscat_id');
            return $query->whereNewscatId($id);
        }
    }

    /**
     * Select only news that are published
     */
    public function scopePublished($query)
    {
        return $query->wherePublished(true)->where('published_at', '<=', DB::raw('CURRENT_TIMESTAMP'));
    }

    /**
     * Count the comments that are related to this news.
     * 
     * @return int
     */
    public function countComments()
    {
        return Comment::count('news', $this->id);
    }

    /**
     * Create an instance of OpenGraph that represents Open Graph tags.
     * 
     * @return array
     */
    public function openGraph()
    {    
        $og = new OpenGraph(true);

        $og->title($this->title)
            ->type('article')
            ->image($this->newscat->uploadPath().$this->newscat->image)
            ->description($this->intro)
            ->url();

        return $og;
    }

    /**
     * Create/update RSS file
     * 
     * @return void
     */
    public static function updateRSS() 
    {
        $feed = Rss::feed('2.0', 'UTF-8');

        $feed->channel([
            'title'         => Config::get('app.title').' '.trans('news::rss_news'), 
            'description'   => trans('news::rss_last'), 
            'language'      => Lang::getLocale(),
            'link'          => Config::get('app.url'),
            'lastBuildDate' => date('D, j M Y H:i:s ').'GMT'
        ]);

        $newsCollection = News::published()->orderBy('created_at', 'DESC')->take(20)->get();
        foreach ($newsCollection as $news) {
            $url = URL::route('news.show', $news->id);

            $feed->item([
                'title'             => $news->title, 
                'description|cdata' => $news->intro, 
                'link'              => $url,
                'guid'              => $url,
                'pubDate'           => date('D, j M Y H:i:s ', $news->created_at->timestamp).'GMT'
            ]);
        }

        $feed->save(public_path().'/rss/news.xml');
    }
}