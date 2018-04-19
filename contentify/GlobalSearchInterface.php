<?php

namespace Contentify;

/**
 * Interface for controllers that provide results for the global search in the frontend.
 */
interface GlobalSearchInterface {

    /**
     * This method is called by the global search (SearchController->postCreate()).
     * Its purpose is to return an array with results for a specific search query.
     * These results may contain HTML code for example to generate links to the results.
     *
     * @param  string $subject The search term
     * @return string[]
     */
    public function globalSearch($subject);

}