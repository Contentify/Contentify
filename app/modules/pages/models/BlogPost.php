<?php namespace App\Modules\Pages\Models;

use App\Modules\Pages\Models\Page;
use DB;

class BlogPost extends Page {

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

}