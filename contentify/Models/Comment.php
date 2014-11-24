<?php namespace Contentify\Models;

use Cache, SoftDeletingTrait, BBCode;

class Comment extends BaseModel {

    use SoftDeletingTrait;

    const CACHE_KEY = 'comments.comment.';

    protected $fillable = array('text');

    public static $rules = array(
        'text'  => 'required|min:3',
    );

    public static $relationsData = array(
        'creator' => array(self::BELONGS_TO, 'User'),
    );

    public static function boot()
    {
        parent::boot();

        self::saved(function ($comment)
        {
            $comment->cache();
        });
    }

    /**
     * Caches this comment - we don't want to parse BBCodes each time
     * we want to display a comment.
     * 
     * @return void
     */
    public function cache()
    {
        $bbcode = new BBCode();
        $rendered = $bbcode->render($this->text);
        Cache::put(self::CACHE_KEY.$this->id, $rendered, 60);
    }

    /**
     * Counts the comments that are related to a certain foreign type (model).
     * NOTE: The result of the database query is cached!
     * 
     * @param  string   $foreignType Name of the foreign type (model)
     * @param  int      $foreignId   ID of the foreign type or null
     * @return int
     */
    public static function count($foreignType, $foreignId = null)
    {
        $query = self::remember(5)->whereForeignType($foreignType);
        if ($foreignId) $query->whereForeignId($foreignId);
        return $query->count();
    }

    /**
     * Renders the comment's text (with BBCode converted to HTML code)
     * 
     * @return string
     */
    public function renderText()
    {
        $key = self::CACHE_KEY.$this->id;

        if (! Cache::has($key)) {
            $this->cache();
        }

        return Cache::get($key);
    }

    /**
     * Return just the plain comment text.
     * (Similar to render BBCode without the tags but it uses caching.)
     * 
     * @return string
     */
    public function plain()
    {
        return strip_tags($this->render());
    }

}