<?php

namespace App\Modules\News;

use BaseModel;
use Comment;
use Config;
use ContentFilter;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Lang;
use OpenGraph;
use Rss;
use SoftDeletingTrait;
use URL;

/**
 * @property \Carbon                   $created_at
 * @property \Carbon                   $deleted_at
 * @property \Carbon                   $published_at
 * @property string                    $title
 * @property string                    $slug
 * @property string                    $summary
 * @property string                    $text
 * @property bool                      $published
 * @property bool                      $internal
 * @property bool                      $enable_comments
 * @property int                       $news_cat_id
 * @property int                       $access_counter
 * @property int                       $creator_id
 * @property int                       $updater_id
 * @property \App\Modules\News\NewsCat $newsCat
 * @property \User                     $creator
 */
class News extends BaseModel
{

    use SoftDeletingTrait;

    protected $dates = ['deleted_at', 'published_at'];

    protected $slugable = true;

    protected $fillable = [
        'title', 
        'summary', 
        'text', 
        'published', 
        'published_at',
        'internal', 
        'enable_comments', 
        'news_cat_id',
        'creator_id'
    ];

    protected $rules = [
        'title'             => 'required|min:3',
        'published'         => 'boolean',
        'internal'          => 'boolean',
        'enable_comments'   => 'boolean',
        'news_cat_id'       => 'required|integer',
        'creator_id'        => 'required|integer',
    ];

    public static $relationsData = [
        'newsCat' => [self::BELONGS_TO, 'App\Modules\News\NewsCat'],
        'creator' => [self::BELONGS_TO, 'User', 'title' => 'username'],
    ];

    public static function boot()
    {
        parent::boot();

        self::saved(function(self $news)
        {
           self::updateRSS();
        });

        self::deleted(function(self $news)
        {
           self::updateRSS();
        });
    }

    /**
     * Select only news that match filter criteria such as the category ID
     *
     * @param Builder $query
     * @return Builder|null
     */
    public function scopeFilter($query)
    {
        if (ContentFilter::has('news_cat_id')) {
            $id = (int) ContentFilter::get('news_cat_id');
            return $query->whereNewsCatId($id);
        }

        return null;
    }

    /**
     * Select only news that have been published
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePublished(Builder $query)
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
     * @return OpenGraph
     */
    public function openGraph()
    {
        $og = new OpenGraph(true);

        $og->title($this->title)
            ->type('article')
            ->image($this->newsCat->uploadPath().$this->newsCat->image)
            ->description($this->summary)
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
            'title'         => Config::get('app.name').' '.trans('app.object_news'),
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
                'description|cdata' => $news->summary, 
                'link'              => $url,
                'guid'              => $url,
                'pubDate'           => date('D, j M Y H:i:s ', $news->created_at->timestamp).'GMT'
            ]);
        }

        $feed->save(public_path().'/rss/news.xml');
    }
}