<?php

namespace Contentify;

use Illuminate\Pagination\LengthAwarePaginator as OriginalPaginator;
use Request;

/**
 * This class provides a work-around for a change in the way paginators work.
 * This change has been made by Laravel, so we have to overwrite Laravel's
 * original paginator with our own.
 */
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
