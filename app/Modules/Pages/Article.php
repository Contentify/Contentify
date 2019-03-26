<?php

namespace App\Modules\Pages;

use Comment;
use DB;
use Illuminate\Database\Eloquent\Builder;
use OpenGraph;

class Article extends Page
{

    protected $isSubclass = true;

    protected $subclassId = 1;

    /**
     * Select only pages that have been published
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePublished(Builder $query) : Builder
    {
        return $query->wherePublished(true)->where('published_at', '<=', DB::raw('CURRENT_TIMESTAMP'));
    }

    /**
     * Count the comments that are related to this page.
     * 
     * @return int
     */
    public function countComments() : int
    {
        return Comment::count('pages', $this->id);
    }

    /**
     * Create an instance of OpenGraph that represents Open Graph tags.
     *
     * @return OpenGraph
     * @throws \Exception
     */
    public function openGraph() : OpenGraph
    {
        $og = new OpenGraph();

        $og->title($this->title)
            ->type('article')
            ->description($this->text)
            ->url();

        return $og;
    }
}
