<?php

namespace App\Modules\Pages;

use DB;
use Illuminate\Database\Eloquent\Builder;

class CustomPage extends Page
{

    protected $isSubclass = true;

    protected $subclassId = 2;

    /**
     * Select only pages that have been published
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePublished($query)
    {
        return $query->wherePublished(true)->where('published_at', '<=', DB::raw('CURRENT_TIMESTAMP'));
    }

}