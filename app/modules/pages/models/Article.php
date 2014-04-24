<?php namespace App\Modules\Pages\Models;

use App\Modules\Pages\Models\Page;
use OpenGraph, DB;

class Article extends Page {

    /**
     * Select only pages that are published
     */
    public function scopePublished($query)
    {
        return $query->wherePublished(true)->where('published_at', '<=', DB::raw('CURRENT_TIMESTAMP'));
    }

    /**
     * Count the comments that are related to this page.
     * 
     * @return int
     */
    public function countComments()
    {
        return Comment::count('pages', $this->id);
    }

    /**
     * Create an instance of OpenGraph that represents Open Graph tags.
     * 
     * @return array
     */
    public function openGraph()
    {    
        $og = new OpenGraph();

        $og->title($this->title)
            ->type('article')
            ->description($this->text)
            ->url();

        return $og;
    }

}