<?php

namespace Contentify;

use Input;

class ContentFilter
{

    /**
     * True if the content filter exists
     * 
     * @param string $name The name of the content filter
     * @return boolean
     */
    public function has($name)
    {
        if (! Input::has('filter')) {
            return false;
        }

        $filters = Input::get('filter');
        $filters = explode(',', $filters);

        foreach ($filters as $filter) {
            if (! $filter) continue;

            $parts = explode('-', $filter);

            if ($parts[0] == $name) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns the value of a content filter. 
     * Returns null if no value is set.
     * NOTE: Use has() to check if a content filter exists, because
     * get() might return null even if a content filter exists.
     * (Null is a valid value.)
     * 
     * @param string      $name The name of the content filter
     * @return string|null
     */
    public function get($name)
    {
        if (! Input::has('filter')) {
            return null;
        }

        $filters = Input::get('filter');
        $filters = explode(',', $filters);

        foreach ($filters as $filter) {
            if (! $filter) continue;

            $parts = explode('-', $filter);

            if ($parts[0] == $name) {
                return (isset($parts[1]) ? $parts[1] : null);
            }
        }

        return null;
    }

}