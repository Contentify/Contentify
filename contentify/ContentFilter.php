<?php

namespace Contentify;

use Request;

/**
 * When - for example - users are on the news index page where they see a list of published news,
 * they might want to filter, for example by the category of the news. This can be achieved
 * by using content filters. This class has methods that can check if a content filter of a
 * given name has been set in the current request and can retrieve its value.
 */
class ContentFilter
{

    /**
     * True if a given content filter is set in the current request
     *
     * @param string $name The name of the content filter
     * @return bool
     */
    public function has(string $name) : bool
    {
        if (! Request::has('filter')) {
            return false;
        }

        $filters = Request::get('filter');
        $filters = explode(',', $filters);

        foreach ($filters as $filter) {
            if (! $filter) {
                continue;
            }

            $parts = explode('-', $filter);

            if ($parts[0] == $name) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns the value of a given content filter.
     * Returns null if no value is set.
     * NOTE: Use has() to check if a content filter exists, because
     * get() might return null even if a content filter exists,
     * because null is a valid value.
     *
     * @param string      $name The name of the content filter
     * @return string|null
     */
    public function get(string $name): ?string
    {
        if (! Request::has('filter')) {
            return null;
        }

        $filters = Request::get('filter');
        $filters = explode(',', $filters);

        foreach ($filters as $filter) {
            if (! $filter) {
                continue;
            }

            $parts = explode('-', $filter);

            if ($parts[0] == $name) {
                return $parts[1] ?? null;
            }
        }

        return null;
    }
}
