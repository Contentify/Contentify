<?php

namespace Contentify;

use Illuminate\Pagination\LengthAwarePaginator as OriginalPaginator;
use Request;

class LengthAwarePaginator extends OriginalPaginator
{

    /**
     * Create a new paginator instance.
     *
     * @param mixed    $items
     * @param int      $total
     * @param int      $perPage
     * @param int|null $currentPage
     * @param array    $options (path, query, fragment, pageName)
     */
    public function __construct($items, $total, $perPage, $currentPage = null, array $options = [])
    {
        parent::__construct($items, $total, $perPage, $currentPage, $options);

        /*
         * Set the $path variable to the current URL.
         */
        $this->path = Request::url();
    }

}